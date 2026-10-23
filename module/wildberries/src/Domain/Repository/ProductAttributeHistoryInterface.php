<?php

declare(strict_types=1);

namespace Wildberries\Domain\Repository;

use Wildberries\Domain\Entity\ProductAttributeHistory;

interface ProductAttributeHistoryInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return ProductAttributeHistory|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?ProductAttributeHistory;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return ProductAttributeHistory[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param string $productId
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByProductId(string $productId, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param string $productId
     * @return int
     */
    public function getRowsCount(string $productId): int;
}
