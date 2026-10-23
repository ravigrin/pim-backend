<?php

declare(strict_types=1);

namespace Ozon\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;

#[ORM\Entity]
#[ORM\Table('ozon_attribute_group')]
class AttributeGroup extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid   $attributeGroupUuid,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string $name,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string $alias,
        /**
         *
         */
        #[ORM\Column]
        private int    $customOrder,
        /**
         *
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function getAttributeGroupUuid(): Uuid
    {
        return $this->attributeGroupUuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getCustomOrder(): int
    {
        return $this->customOrder;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


}
