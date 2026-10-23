<?php

declare(strict_types=1);

namespace Ozon\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;

#[ORM\Entity]
#[ORM\Table('ozon_attribute_tab')]
class AttributeTab extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid   $attributeTabUuid,
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

    public function getAttributeTabId(): Uuid
    {
        return $this->attributeTabUuid;
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
