<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository;

use Ozon\Domain\Entity\AttributeGroup;
use Shared\Domain\ValueObject\Uuid;

interface AttributeGroupInterface
{
    public function findByAlias(string $alias): ?AttributeGroup;

    /**
     * @return AttributeGroup[]
     */
    public function getAll(): array;

    public function findAttributeGroup(Uuid $catalogId): array;

}
