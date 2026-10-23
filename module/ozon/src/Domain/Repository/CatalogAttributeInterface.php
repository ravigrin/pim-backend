<?php

namespace Ozon\Domain\Repository;

use Ozon\Domain\Entity\CatalogAttribute;
use Shared\Domain\ValueObject\Uuid;

interface CatalogAttributeInterface
{
    public function findOneByCatalogIdAttributeId(Uuid $catalogId, Uuid $attributeId): ?CatalogAttribute;
}
