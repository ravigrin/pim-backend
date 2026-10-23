<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ozon\Domain\Entity\ProductAttributeHistory;
use Ozon\Domain\Repository\ProductAttributeHistoryInterface;

class ProductAttributeHistoryRepository implements ProductAttributeHistoryInterface
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

    /**
     * @param string $productId
     * @return int
     * @throws Exception
     */
    public function getRowsCount(string $productId): int
    {
        $sql = "SELECT count(PAH.product_attribute_history_uuid) FROM ozon_product_attribute_history PAH
                LEFT JOIN ozon_product_attribute PA ON PA.attribute_uuid = PAH.product_attribute_uuid
                WHERE PA.product_uuid = '%s'";

        return (int)$this->connection->executeQuery(sprintf($sql, $productId))->fetchOne();
    }
}
