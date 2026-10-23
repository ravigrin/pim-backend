<?php

declare(strict_types=1);

namespace Pim\Domain\Repository\Pim;

use Pim\Domain\Entity\ProductAttributeHistory;

interface ProductAttributeHistoryInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?ProductAttributeHistory;

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
     * Ищет историю по массиву id
     * @param int[] $ids
     * @return ProductAttributeHistory[]|null
     * @throws \Exception
     */
    public function findByIds(array $ids, int $page, int $perPage): array|null;


    /**
     * @param string $productId
     * @return int
     */
    public function getRowsCount(string $productId): int;
}
