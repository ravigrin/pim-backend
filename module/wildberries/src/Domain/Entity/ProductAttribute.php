<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\ProductAttributeCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_product_attribute')]
#[ORM\HasLifecycleCallbacks]
class ProductAttribute extends AggregateRoot
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private string $productAttributeId,
        #[ORM\Column(type: 'uuid')]
        private string $productId,
        #[ORM\Column(type: 'uuid')]
        private string $attributeId,
        /**
         * Пользовательские значения (сохраняем как есть)
         * @var string[] $value
         */
        #[ORM\Column(type: Types::JSON)]
        private array  $value,
        #[ORM\Column(length: 4000)]
        private string $hash,
        #[ORM\Column(nullable: true)]
        private ?int   $wbAttributeId = null
    )
    {
        $this->apply(new ProductAttributeCreated(
            productAttributeId: $this->productAttributeId,
            productId: $this->productId,
            attributeId: $this->attributeId,
            value: $this->value,
            hash: $this->hash,
            wbAttributeId: $this->wbAttributeId
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

    public function getProductAttributeId(): string
    {
        return $this->productAttributeId;
    }

    public function setProductAttributeId(string $productAttributeId): void
    {
        $this->productAttributeId = $productAttributeId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    public function getAttributeId(): string
    {
        return $this->attributeId;
    }

    public function setAttributeId(string $attributeId): void
    {
        $this->attributeId = $attributeId;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function setValue(array $value): void
    {
        $this->value = $value;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getWbAttributeId(): ?int
    {
        return $this->wbAttributeId;
    }

    public function setWbAttributeId(?int $wbAttributeId): void
    {
        $this->wbAttributeId = $wbAttributeId;
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
