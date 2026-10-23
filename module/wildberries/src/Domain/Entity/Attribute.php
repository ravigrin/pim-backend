<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\AttributeCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_attribute')]
#[ORM\HasLifecycleCallbacks]
class Attribute extends AggregateRoot
{
    /**
     * @param string $attributeId
     * @param string $name
     * @param string $type
     * @param int $charcType
     * @param int $maxCount
     * @param string $source
     * @param string|null $measurement
     * @param string|null $description
     * @param string|null $alias
     * @param string|null $groupId
     * @param array|null $defaultValue
     * @param bool $isRequired
     * @param bool $isPopular
     * @param bool $isDictionary
     * @param bool $isReadOnly
     * @param bool $isVisible
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::GUID)]
        private string  $attributeId,
        #[ORM\Column(length: 1024)]
        private string  $name,
        #[ORM\Column(length: 64)]
        private string  $type,

        /**
         * @var int $charcType wildberries attribute (characteristic) type
         */
        #[ORM\Column]
        private int     $charcType,
        #[ORM\Column]
        private int     $maxCount,
        #[ORM\Column(length: 55, nullable: true)]
        private string  $source,
        #[ORM\Column(length: 8, nullable: true)]
        private ?string $measurement = null,
        #[ORM\Column(length: 1024, nullable: true)]
        private ?string $description = null,
        #[ORM\Column(length: 55, nullable: true)]
        private ?string $alias = null,
        #[ORM\Column(type: Types::GUID, nullable: true)]
        private ?string $groupId = null,
        #[ORM\Column(type: Types::JSON, nullable: true)]
        private ?array  $defaultValue = null,
        #[ORM\Column]
        private bool    $isRequired = false,
        #[ORM\Column]
        private bool    $isPopular = false,
        #[ORM\Column]
        private bool    $isDictionary = false,
        #[ORM\Column]
        private bool    $isReadOnly = false,
        #[ORM\Column]
        private bool    $isVisible = true
    )
    {
        $this->apply(new AttributeCreated(
            attributeId: $this->attributeId,
            name: $this->name,
            type: $this->type,
            charcType: $this->charcType,
            maxCount: $this->maxCount,
            source: $this->source,
            isRequired: $this->isRequired,
            isPopular: $this->isPopular,
            isDictionary: $this->isDictionary,
            isReadOnly: $this->isReadOnly,
            groupId: $this->groupId,
            alias: $this->alias,
            measurement: $this->measurement,
            description: $this->description,
            isVisible: $this->isVisible
        ));
    }

    #[ORM\Column]
    private bool $isDeleted = false;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    /**
     * Gets triggered only on insert
     */
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime("now");
    }

    /**
     * Gets triggered every time on update
     */
    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime("now");
    }

    public function getAttributeId(): string
    {
        return $this->attributeId;
    }

    public function setAttributeId(string $attributeId): void
    {
        $this->attributeId = $attributeId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getCharcType(): int
    {
        return $this->charcType;
    }

    public function setCharcType(int $charcType): void
    {
        $this->charcType = $charcType;
    }

    public function getMaxCount(): ?int
    {
        return $this->maxCount;
    }

    public function setMaxCount(?int $maxCount): void
    {
        $this->maxCount = $maxCount;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getMeasurement(): ?string
    {
        return $this->measurement;
    }

    public function setMeasurement(?string $measurement): void
    {
        $this->measurement = $measurement;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    public function setGroupId(?string $groupId): void
    {
        $this->groupId = $groupId;
    }

    public function getDefaultValue(): ?array
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(?array $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    public function isPopular(): bool
    {
        return $this->isPopular;
    }

    public function setIsPopular(bool $isPopular): void
    {
        $this->isPopular = $isPopular;
    }

    public function isDictionary(): bool
    {
        return $this->isDictionary;
    }

    public function setIsDictionary(bool $isDictionary): void
    {
        $this->isDictionary = $isDictionary;
    }

    public function isReadOnly(): bool
    {
        return $this->isReadOnly;
    }

    public function setIsReadOnly(bool $isReadOnly): void
    {
        $this->isReadOnly = $isReadOnly;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

}
