<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\ProductAttributeHistoryCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_product_attribute_history')]
#[ORM\HasLifecycleCallbacks]
class ProductAttributeHistory extends AggregateRoot
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::GUID)]
        private string $productAttributeHistoryId,
        #[ORM\Column(type: Types::GUID)]
        private string $userId,
        #[ORM\Column(type: Types::GUID)]
        private string $productAttributeId,
        /**
         * @var string
         */
        #[ORM\Column(type: Types::JSON)]
        private array  $newValue,

        /**
         * @var string|null
         */
        #[ORM\Column(type: Types::JSON, nullable: true)]
        private ?array $oldValue = null
    ) {
        $this->apply(new ProductAttributeHistoryCreated(
            productAttributeHistoryId: $this->productAttributeHistoryId,
            userId: $this->userId,
            productAttributeId: $this->productAttributeId,
            newValue: $this->newValue,
            oldValue: $this->oldValue
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

    public function getProductAttributeHistoryId(): string
    {
        return $this->productAttributeHistoryId;
    }

    public function setProductAttributeHistoryId(string $productAttributeHistoryId): void
    {
        $this->productAttributeHistoryId = $productAttributeHistoryId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getProductAttributeId(): string
    {
        return $this->productAttributeId;
    }

    public function setProductAttributeId(string $productAttributeId): void
    {
        $this->productAttributeId = $productAttributeId;
    }

    public function getOldValue(): ?array
    {
        return $this->oldValue;
    }

    public function setOldValue(?array $oldValue): void
    {
        $this->oldValue = $oldValue;
    }

    public function getNewValue(): array
    {
        return $this->newValue;
    }

    public function setNewValue(array $newValue): void
    {
        $this->newValue = $newValue;
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
