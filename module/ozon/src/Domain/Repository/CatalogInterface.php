<?php

namespace Ozon\Domain\Repository;

use Ozon\Domain\Entity\Catalog;

interface CatalogInterface
{
    /**
     * @return Catalog[]
     */
    public function findAll(): array;

    public function findOneByExternalId(int $externalId): ?Catalog;

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Catalog;
}
