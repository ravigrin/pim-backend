<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Wildberries\Domain\Entity\ProductAttributeHistory;
use Wildberries\Domain\Repository\ProductAttributeHistoryInterface;
use Exception;

/**
 * Репозиторий для работы с иторией характеристик (атрибутов) товаров Wildberries
 */
class ProductAttributeHistoryRepository implements ProductAttributeHistoryInterface
{
    /** @psalm-var EntityRepository<ProductAttributeHistory> */
    private EntityRepository $repository;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly Connection             $connection,
        private readonly EntityManagerInterface $entityManager
    )
    {
        $this->repository = $this->entityManager->getRepository(ProductAttributeHistory::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return ProductAttributeHistory|null
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?ProductAttributeHistory
    {
        return $this->repository->findOneBy(
            array_merge(
                $criteria,
                ['isDeleted' => false]
            ),
            $orderBy
        );
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return ProductAttributeHistory[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array
    {
        return $this->repository->findBy(
            array_merge(
                $criteria,
                ['isDeleted' => false]
            ),
            $orderBy,
            $limit,
            $offset,
        );
    }

    /**
     * @inheritDoc
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByProductId(
        string $productId,
        ?int   $limit = null,
        ?int   $offset = null
    ): array
    {
        $sql = "SELECT PAH.*, PA.attribute_id FROM wb_product_attribute_history PAH
                LEFT JOIN wb_product_attribute PA ON PA.product_attribute_id = PAH.product_attribute_id
                WHERE PA.product_id = '%s' ORDER BY PAH.created_at DESC 
                OFFSET %s ROWS FETCH NEXT %s ROWS ONLY";

        return $this->connection->executeQuery(sprintf($sql, $productId, $offset, $limit))->fetchAllAssociative();
    }

    /**
     * @param string $productId
     * @return int
     * @throws \Doctrine\DBAL\Exception
     */
    public function getRowsCount(string $productId): int
    {
        $sql = "SELECT count(PAH.product_attribute_history_id) FROM wb_product_attribute_history PAH
                LEFT JOIN wb_product_attribute PA ON PA.product_attribute_id = PAH.product_attribute_id
                WHERE PA.product_id = '%s'";

        return (int)$this->connection->executeQuery(sprintf($sql, $productId))->fetchOne();
    }

}
