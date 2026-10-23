<?php

declare(strict_types=1);

namespace Ozon\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;

/**
 *
 */
#[ORM\Entity]
#[ORM\Table('ozon_attribute')]
class Attribute extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid  $attributeUuid,
        /**
         *
         */
        #[ORM\Column(type: 'uuid', nullable: true)]
        private readonly ?Uuid $catalogUuid,
        /**
         *
         */
        #[ORM\Column]
        private int            $catalogId,
        /**
         *
         */
        #[ORM\Column]
        private int            $typeId,
        /**
         *
         */
        #[ORM\Column]
        private int            $attributeId,
        /**
         *
         */
        #[ORM\Column]
        private int            $attributeComplexId,
        /**
         *
         */
        #[ORM\Column(length: 1000)]
        private string         $name,
        /**
         *
         */
        #[ORM\Column(length: 4000)]
        private string         $description,
        /**
         *
         */
        #[ORM\Column(length: 256)]
        private string         $pimType,
        /**
         *
         */
        #[ORM\Column(length: 256)]
        private string         $type,
        /**
         *
         */
        #[ORM\Column]
        private bool           $isCollection,
        /**
         *
         */
        #[ORM\Column]
        private bool           $isRequired,
        /**
         *
         */
        #[ORM\Column]
        private bool           $isAspect,
        /**
         *
         */
        #[ORM\Column]
        private int            $maxValueCount,
        /**
         *
         */
        #[ORM\Column(length: 256)]
        private string         $groupName,
        /**
         *
         */
        #[ORM\Column]
        private int            $groupId,
        /**
         *
         */
        #[ORM\Column]
        private int            $dictionaryId,
        /**
         *
         */
        #[ORM\Column(length: 256, nullable: true)]
        private ?string        $alias = null,
        /**
         *
         */
        #[ORM\Column(length: 40, nullable: true)]
        private ?string        $unionFlag = null,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private ?Uuid          $tabUuid = null,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private ?Uuid          $groupUuid = null,
        /**
         *
         */
        #[ORM\Column(options: ["default" => false])]
        private bool           $readOnly = false,
        /**
         *
         */
        #[ORM\Column]
        private bool           $isVisible = true,
        /**
         *
         */
        #[ORM\Column]
        private bool           $isDeleted = false,
    ) {
    }

    public function setCatalogId(int $catalogId): void
    {
        $this->catalogId = $catalogId;
    }

    public function setTypeId(int $typeId): void
    {
        $this->typeId = $typeId;
    }

    public function setAttributeId(int $attributeId): void
    {
        $this->attributeId = $attributeId;
    }

    public function setAttributeComplexId(int $attributeComplexId): void
    {
        $this->attributeComplexId = $attributeComplexId;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPimType(string $pimType): void
    {
        $this->pimType = $pimType;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setIsCollection(bool $isCollection): void
    {
        $this->isCollection = $isCollection;
    }

    public function setIsRequired(bool $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    public function setIsAspect(bool $isAspect): void
    {
        $this->isAspect = $isAspect;
    }

    public function setMaxValueCount(int $maxValueCount): void
    {
        $this->maxValueCount = $maxValueCount;
    }

    public function setGroupName(string $groupName): void
    {
        $this->groupName = $groupName;
    }

    public function setGroupId(int $groupId): void
    {
        $this->groupId = $groupId;
    }

    public function setDictionaryId(int $dictionaryId): void
    {
        $this->dictionaryId = $dictionaryId;
    }

    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    public function setUnionFlag(?string $unionFlag): void
    {
        $this->unionFlag = $unionFlag;
    }

    public function setTabUuid(?Uuid $tabUuid): void
    {
        $this->tabUuid = $tabUuid;
    }

    public function setGroupUuid(?Uuid $groupUuid): void
    {
        $this->groupUuid = $groupUuid;
    }

    public function setReadOnly(bool $readOnly): void
    {
        $this->readOnly = $readOnly;
    }

    public function setIsVisible(bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function getCatalogUuid(): ?Uuid
    {
        return $this->catalogUuid;
    }

    public function getAttributeUuid(): Uuid
    {
        return $this->attributeUuid;
    }

    public function getCatalogId(): int
    {
        return $this->catalogId;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function getAttributeId(): int
    {
        return $this->attributeId;
    }

    public function getAttributeComplexId(): int
    {
        return $this->attributeComplexId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPimType(): string
    {
        return $this->pimType;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isCollection(): bool
    {
        return $this->isCollection;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function isAspect(): bool
    {
        return $this->isAspect;
    }

    public function getMaxValueCount(): int
    {
        return $this->maxValueCount;
    }

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function getDictionaryId(): int
    {
        return $this->dictionaryId;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function getUnionFlag(): ?string
    {
        return $this->unionFlag;
    }

    public function getTabUuid(): ?Uuid
    {
        return $this->tabUuid;
    }

    public function getGroupUuid(): ?Uuid
    {
        return $this->groupUuid;
    }

    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

}
