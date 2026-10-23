<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository;

use Ozon\Domain\Entity\AttributeTab;
use Shared\Domain\ValueObject\Uuid;

interface AttributeTabInterface
{
    public function findByAlias(string $alias): ?AttributeTab;

    /**
     * @return array<string, Uuid>
     */
    public function finAllWithAliasUuid(): array;

}
