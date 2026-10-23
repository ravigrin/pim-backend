<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('pim_attribute')]
#[ORM\HasLifecycleCallbacks]
class Attribute extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(length: 40, unique: true)]
        private string  $attributeId,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string  $name,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string  $alias,
        /**
         *
         */
        #[ORM\Column(length: 40, nullable: true)]
        private ?string $tabId = null,
        /**
         *
         */
        #[ORM\Column(length: 40, nullable: true)]
        private ?string $groupId = null,
        /**
         *
         */
        #[ORM\Column(length: 4000, nullable: true)]
        private ?string $description = null,
        /**
         *
         */
        #[ORM\Column]
        private bool    $isRequired = false,
        /**
         *
         */
        #[ORM\Column(length: 1024, nullable: true)]
        private ?string $pimType = null,
        /**
         *
         */
        #[ORM\Column]
        private int     $maxCount = 1,

        #[ORM\Column(length: 8, nullable: true)]
        private ?string $measurement = null,
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string  $attributeGroupId = "",
        /**
         *
         */
        #[ORM\Column(options: ["default" => true])]
        private bool    $isVisible = true,
        /**
         *
         */
        #[ORM\Column(options: ["default" => 0])]
        private bool    $readOnly = false,
        /**
         *
         */
        #[ORM\Column]
        private bool    $isDeleted = false,
    ) {
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function setTabId(?string $tabId): void
    {
        $this->tabId = $tabId;
    }

    public function setGroupId(?string $groupId): void
    {
        $this->groupId = $groupId;
    }

    public function getTabId(): ?string
    {
        return $this->tabId;
    }

    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    public function getAttributeId(): string
    {
        return $this->attributeId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function getPimType(): ?string
    {
        return $this->pimType;
    }

    public function getMaxCount(): int
    {
        return $this->maxCount;
    }

    public function getMeasurement(): ?string
    {
        return $this->measurement;
    }

    public function setMeasurement(?string $measurement): void
    {
        $this->measurement = $measurement;
    }

    public function getAttributeGroupId(): string
    {
        return $this->attributeGroupId;
    }

    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


}
