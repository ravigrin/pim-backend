<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Pim\Domain\Entity\ProductAttributeHistory;
use Pim\Domain\Repository\Pim\ProductAttributeHistoryInterface;

final readonly class ProductAttributeHistoryRepository implements ProductAttributeHistoryInterface
{
    /** @psalm-var EntityRepository<ProductAttributeHistory> */
    private EntityRepository $repository;

    public function __construct(
        private Connection             $connection,
        private EntityManagerInterface $entityManager,
    )
    {
        $this->repository = $this->entityManager->getRepository(ProductAttributeHistory::class);
    }

    /**
     * @param int[] $ids
     * @param int $page
     * @param int $perPage
     * @return ProductAttributeHistory[]|null
     * @throws Exception
     */
    public function findByIds(array $ids, int $page, int $perPage): array|null
    {
        $offset = 0;
        if ($page > 1) {
            $offset += $perPage * ($page - 1);
        }

        $qb = $this->repository->createQueryBuilder('history')
            ->where('history.productAttribute IN (:ids)')
            ->addOrderBy('history.dateCreate', 'DESC')
            ->setParameter(':ids', $ids)
            ->setMaxResults($perPage);

        if ($offset > 0) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $productId
     * @return int
     * @throws \Doctrine\DBAL\Exception
     */
    public function getRowsCount(string $productId): int
    {
        $sql = "SELECT count(PAH.product_attribute_history_id) FROM pim_product_attribute_history PAH
                WHERE PAH.product_id = '%s'";

        return (int)$this->connection->executeQuery(sprintf($sql, $productId))->fetchOne();
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?ProductAttributeHistory
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return ProductAttributeHistory[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array
    {
        return $this->repository->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset,
        );
    }
}
