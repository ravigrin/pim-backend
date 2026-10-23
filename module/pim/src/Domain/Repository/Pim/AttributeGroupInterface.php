<?php

declare(strict_types=1);

namespace Pim\Domain\Repository\Pim;

use Pim\Domain\Entity\AttributeGroup;

interface AttributeGroupInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?AttributeGroup;

    public function findById(int $attributeGroupId): ?AttributeGroup;

    public function findByAlias(string $alias): ?AttributeGroup;

    /**
     * @return AttributeGroup[]
     */
    public function getAll(): array;

}
