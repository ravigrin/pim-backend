<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\ProductCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_product')]
#[ORM\HasLifecycleCallbacks]
class Product extends AggregateRoot
{
    /**
     * @param string $productId
     * @param int|null $exportStatus
     * @param string|null $catalogId
     * @param int|null $imtId
     * @param int|null $nmId
     * @param string|null $vendorCode
     * @param string|null $brand
     * @param string|null $title
     * @param string|null $description
     * @param string|null $sellerName
     * @param string|null $nmUuid
     * @param array|null $dimensions
     * @param DateTime|null $wbUpdatedAt
     * @param array|null $tags
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private string    $productId,
        /**
         * Статусы:
         * 0 - новый/измененный
         * 1 - отправка
         * 2 - отправлено (успешно)
         * 3 - ошибка
         *
         * @var int $exportStatus Статус отправки товара на маркетплейс Wildberries
         */
        #[ORM\Column(options: ["default" => 0])]
        private ?int      $exportStatus,
        #[ORM\Column(type: 'uuid', nullable: true)]
        private ?string   $catalogId = null,
        /**
         * @var int $imtId Wildberries - Идентификатор карточки товара
         */
        #[ORM\Column(nullable: true)]
        private ?int      $imtId = null,
        /**
         * @var int $nmId Wildberries - Идентификатор номенклатуры
         */
        #[ORM\Column(unique: true, nullable: true)]
        private ?int      $nmId = null,
        #[ORM\Column(length: 256, nullable: true)]
        private ?string   $vendorCode = null,
        #[ORM\Column(length: 256, nullable: true)]
        private ?string   $brand = null,
        #[ORM\Column(length: 1000, nullable: true)]
        private ?string   $title = null,
        #[ORM\Column(length: 2000, nullable: true)]
        private ?string   $description = null,
        #[ORM\Column(length: 128, nullable: true)]
        private ?string   $sellerName = null,
        /**
         * @var string|null $nmUuid - Wildberries nomenclature UUID
         */
        #[ORM\Column(unique: true, nullable: true)]
        private ?string   $nmUuid = null,
        /**
         * @var array|null Габариты упаковки товара.
         */
        #[ORM\Column(type: Types::JSON, nullable: true)]
        private ?array    $dimensions = null,
        /**
         * @var DateTime|null Дата последнего обновления на WB
         */
        #[ORM\Column(type: 'datetime', nullable: true)]
        private ?DateTime $wbUpdatedAt = null,

        #[ORM\Column(type: Types::JSON, nullable: true)]
        private ?array    $tags = null,
    )
    {
        $this->apply(new ProductCreated(
            productId: $productId,
            exportStatus: $exportStatus,
            catalogId: $catalogId,
            imtId: $imtId,
            nmId: $nmId,
            vendorCode: $vendorCode,
            brand: $brand,
            title: $title,
            description: $description,
            sellerName: $sellerName,
            nmUuid: $nmUuid,
            dimensions: $dimensions,
            tags: $tags
        ));
    }

    #[ORM\Column]
    private bool $isActive = true;

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

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    public function getExportStatus(): ?int
    {
        return $this->exportStatus;
    }

    public function setExportStatus(?int $exportStatus): void
    {
        $this->exportStatus = $exportStatus;
    }

    public function getCatalogId(): ?string
    {
        return $this->catalogId;
    }

    public function setCatalogId(?string $catalogId): void
    {
        $this->catalogId = $catalogId;
    }

    public function getImtId(): ?int
    {
        return $this->imtId;
    }

    public function setImtId(?int $imtId): void
    {
        $this->imtId = $imtId;
    }

    public function getNmId(): ?int
    {
        return $this->nmId;
    }

    public function setNmId(?int $nmId): void
    {
        $this->nmId = $nmId;
    }

    public function getVendorCode(): ?string
    {
        return $this->vendorCode;
    }

    public function setVendorCode(?string $vendorCode): void
    {
        $this->vendorCode = $vendorCode;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): void
    {
        $this->brand = $brand;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getSellerName(): ?string
    {
        return $this->sellerName;
    }

    public function setSellerName(?string $sellerName): void
    {
        $this->sellerName = $sellerName;
    }

    public function getNmUuid(): ?string
    {
        return $this->nmUuid;
    }

    public function setNmUuid(?string $nmUuid): void
    {
        $this->nmUuid = $nmUuid;
    }

    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }

    public function setDimensions(?array $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): void
    {
        $this->tags = $tags;
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

    public function getWbUpdatedAt(): ?DateTime
    {
        return $this->wbUpdatedAt;
    }

    public function setWbUpdatedAt(?DateTime $wbUpdatedAt): void
    {
        $this->wbUpdatedAt = $wbUpdatedAt;
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
