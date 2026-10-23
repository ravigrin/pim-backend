<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository;

use Ozon\Domain\Entity\ProductAttributeHistory;

interface ProductAttributeHistoryInterface
{
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
    ): array;

    /**
     * @param string $productId
     * @return int
     */
    public function getRowsCount(string $productId): int;
}
