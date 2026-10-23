<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\AttributeGroupCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_attribute_group')]
#[ORM\HasLifecycleCallbacks]
class AttributeGroup extends AggregateRoot
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::GUID)]
        private string  $attributeGroupId,
        #[ORM\Column(length: 128)]
        private string  $name,
        #[ORM\Column(length: 64)]
        private string  $alias,
        #[ORM\Column(length: 64)]
        private string  $type,
        #[ORM\Column]
        private int     $orderGroup,
        #[ORM\Column(type: Types::GUID, nullable: true)]
        private ?string $tabId = null
    ) {
        $this->apply(new AttributeGroupCreated(
            attributeGroupId: $this->attributeGroupId,
            name: $this->name,
            alias: $this->alias,
            type: $this->type,
            orderGroup: $this->orderGroup,
            tabId: $this->tabId
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

    public function getAttributeGroupId(): string
    {
        return $this->attributeGroupId;
    }

    public function setAttributeGroupId(string $attributeGroupId): void
    {
        $this->attributeGroupId = $attributeGroupId;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getOrderGroup(): int
    {
        return $this->orderGroup;
    }

    public function setOrderGroup(int $orderGroup): void
    {
        $this->orderGroup = $orderGroup;
    }

    public function getTabId(): ?string
    {
        return $this->tabId;
    }

    public function setTabId(?string $tabId): void
    {
        $this->tabId = $tabId;
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
