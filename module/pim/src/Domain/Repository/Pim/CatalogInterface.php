<?php

declare(strict_types=1);

namespace Pim\Domain\Repository\Pim;

use Pim\Domain\Entity\Catalog;

interface CatalogInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Catalog[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array;

    /**
     * @return Catalog[]
     */
    public function findAll(): array;

}
