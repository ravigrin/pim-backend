<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Wildberries\Domain\Entity\Product;
use Wildberries\Domain\Repository\ProductInterface;
use Exception;

/**
 * Репозиторий для работы с товарами Wildberries
 */
class ProductRepository implements ProductInterface
{
    /** @psalm-var EntityRepository<Product> */
    private EntityRepository $repository;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly Connection             $connection,
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Product::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Product|null
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Product {
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
     * @return Product[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
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
     */
    public function findUnion(): array
    {
        $sql = "SELECT product_id, imt_id
                FROM wb_product
                WHERE imt_id IN (
                    SELECT imt_id
                    FROM wb_product
                    GROUP BY imt_id
                    HAVING COUNT(*) > 1
                ) ORDER BY imt_id";

        $products = $this->connection->executeQuery($sql)->fetchAllAssociative();

        $response = [];
        foreach ($products as $product) {
            $response[$product['imt_id']][] = $product['product_id'];
        }

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function findToExport(string $seller): array
    {
        $sql = "SELECT product_id 
                FROM wb_product 
                WHERE export_status != 2 AND seller_name = N'%s'";

        $productIds = $this->connection->executeQuery(sprintf($sql, $seller))->fetchAllAssociative();

        return $this->repository->findBy([
            'productId' => array_column($productIds, 'product_id'),
            'isDeleted' => false,
        ]);
    }

    /**
     * @inheritDoc
     * @throws \Doctrine\DBAL\Exception
     */
    public function getProductsCategories(array $unionIds): array
    {
        return $this->connection->executeQuery(
            sql: "select catalog_id from wb_product where wb_product.product_id in (?)",
            params: $unionIds
        )->fetchFirstColumn();
    }
}
