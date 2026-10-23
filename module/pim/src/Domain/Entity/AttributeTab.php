<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('pim_attribute_tab')]
class AttributeTab extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(length: 40, unique: true)]
        private string $attributeTabId,
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
        private bool   $isDeleted = false
    ) {
    }

    public function getCustomOrder(): int
    {
        return $this->customOrder;
    }

    public function getAttributeTabId(): string
    {
        return $this->attributeTabId;
    }

    public function getAttributeGroupId(): string
    {
        return $this->attributeTabId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

}
