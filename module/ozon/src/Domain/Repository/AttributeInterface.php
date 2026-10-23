<?php

namespace Ozon\Domain\Repository;

use Doctrine\DBAL\Exception;
use Ozon\Domain\Entity\Attribute;
use Shared\Domain\ValueObject\Uuid;

interface AttributeInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Attribute;

    public function findByCatalogType(int $catalogId, int $typeId, int $attributeId): ?Attribute;

    /**
     * @return Attribute[]
     * @throws Exception
     */
    public function findByCatalog(?Uuid $catalogId): array;
}
