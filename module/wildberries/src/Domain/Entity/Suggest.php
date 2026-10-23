<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\SuggestCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_suggest')]
#[ORM\HasLifecycleCallbacks]
class Suggest extends AggregateRoot
{
    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\Column]
    private bool $isDeleted = false;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::GUID)]
        private string $suggestId,
        #[ORM\Column(type: Types::JSON)]
        private array $value,
        #[ORM\Column(length: 256, nullable: true)]
        private ?string $info = null,
        #[ORM\Column(type: Types::GUID, nullable: true)]
        private ?string $attributeId = null,
        #[ORM\Column(type: Types::GUID, nullable: true)]
        private ?string $catalogId = null,
        #[ORM\Column(nullable: true)]
        private ?int $objectId = null,
    ) {

        $this->apply(new SuggestCreated(
            suggestId: $this->suggestId,
            value: $this->value,
            attributeId: $this->attributeId,
            catalogId: $this->catalogId,
            objectId: $this->objectId,
            info: $this->info
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

    public function getSuggestId(): string
    {
        return $this->suggestId;
    }

    public function setSuggestId(string $suggestId): void
    {
        $this->suggestId = $suggestId;
    }

    public function getAttributeId(): string
    {
        return $this->attributeId;
    }

    public function setAttributeId(string $attributeId): void
    {
        $this->attributeId = $attributeId;
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

    public function getValue(): array
    {
        return $this->value;
    }

    public function setValue(array $value): void
    {
        $this->value = $value;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): void
    {
        $this->info = $info;
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
