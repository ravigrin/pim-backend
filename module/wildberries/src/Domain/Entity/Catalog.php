<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\CatalogCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_catalog')]
#[ORM\HasLifecycleCallbacks]
class Catalog extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    private string $catalogId;

    /**
     * @var int $objectId wildberries category id
     */
    #[ORM\Column]
    private int $objectId;

    /**
     * @var int|null $parentId wildberries parent category id
     */
    #[ORM\Column(nullable: true)]
    private ?int $parentId = null;

    #[ORM\Column(length: 1024)]
    private string $name;

    #[ORM\Column(nullable: true)]
    private ?int $level = null;

    /**
     * @var bool wildberries param
     */
    #[ORM\Column]
    private bool $isVisible;

    /**
     * @var bool PIM param
     */
    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\Column]
    private bool $isDeleted = false;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    /**
     * @param string $catalogId
     * @param int $objectId
     * @param int|null $parentId
     * @param string $name
     * @param int|null $level
     * @param bool $isVisible
     */
    public function __construct(
        string   $catalogId,
        int    $objectId,
        string $name,
        bool   $isVisible,
        ?int   $level = null,
        ?int   $parentId = null,
    ) {
        $this->catalogId = $catalogId;
        $this->objectId = $objectId;
        $this->name = $name;
        $this->isVisible = $isVisible;
        if ($parentId) {
            $this->parentId = $parentId;
        }
        if ($level) {
            $this->level = $level;
        }

        $this->apply(new CatalogCreated(
            catalogId: $this->catalogId,
            objectId: $this->objectId,
            name: $this->name,
            level: $this->level,
            parentId: $this->parentId,
            isVisible: $this->isVisible
        ));
    }

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

    public function getCatalogId(): string
    {
        return $this->catalogId;
    }

    public function setCatalogId(string $catalogId): void
    {
        $this->catalogId = $catalogId;
    }

    public function getObjectId(): int
    {
        return $this->objectId;
    }

    public function setObjectId(int $objectId): void
    {
        $this->objectId = $objectId;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): void
    {
        $this->level = $level;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
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
