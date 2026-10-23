<?php

namespace Wildberries\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('wb_catalog_attribute')]
class CatalogAttribute extends AggregateRoot
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::GUID, unique: true)]
        private string $catalogAttributeId,
        #[ORM\Column(length: 40)]
        private string $catalogId,
        #[ORM\Column(length: 40)]
        private string $attributeId,
    ) {
    }

    public function getCatalogAttributeId(): string
    {
        return $this->catalogAttributeId;
    }

    public function getCatalogId(): string
    {
        return $this->catalogId;
    }

    public function getAttributeId(): string
    {
        return $this->attributeId;
    }


}
