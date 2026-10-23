<?php

declare(strict_types=1);

namespace Pim\Domain\Repository\Pim;

use Pim\Domain\Entity\ProductLine;

interface ProductLineInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?ProductLine;

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return ProductLine[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array;

    public function findUuidName(): array;
}
