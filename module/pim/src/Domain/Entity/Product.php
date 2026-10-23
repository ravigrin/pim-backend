<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Pim\Domain\Event\ProductAttributeAdded;

#[ORM\Entity]
#[ORM\Table('pim_product')]
#[ORM\HasLifecycleCallbacks]
class Product extends AggregateRoot
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $dateUpdate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $dateCreate;

    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(length: 40, unique: true)]
        private string  $productId,
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string  $userId,
        /**
         *
         */
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $vendorCode = null,
        /**
         *
         */
        #[ORM\Column(length: 40, nullable: true)]
        private ?string $catalogId = null,
        /**
         *
         */
        #[ORM\Column(length: 40, nullable: true)]
        private ?string $unitId = null,
        /**
         *
         */
        #[ORM\Column(length: 40, nullable: true)]
        private ?string $brandId = null,
        /**
         *
         */
        #[ORM\Column(length: 40, nullable: true)]
        private ?string $productLineId = null,
        /**
         *
         */
        #[ORM\Column(length: 4000, nullable: true)]
        private ?array  $unification = [],
        /**
         *
         */
        #[ORM\Column(options: ["default" => false])]
        private bool    $isKit = false,
        /**
         *
         */
        #[ORM\Column]
        private bool    $isDeleted = false,
    ) {
    }

    public function setCatalogId(?string $catalogId): void
    {
        $this->catalogId = $catalogId;
    }

    public function setVendorCode(?string $vendorCode): void
    {
        $this->vendorCode = $vendorCode;
    }

    public function setUnitId(?string $unitId): void
    {
        $this->unitId = $unitId;
    }

    public function setBrandId(?string $brandId): void
    {
        $this->brandId = $brandId;
    }

    public function setProductLineId(?string $productLineId): void
    {
        $this->productLineId = $productLineId;
    }

    #[ORM\PrePersist]
    public function setUpdatedAtValue(): void
    {
        $this->dateUpdate = new \DateTime();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->dateCreate = new \DateTime();
    }

    public function setUnification(array $unification): void
    {
        $this->unification = $unification;
    }

    public function setIsKit(bool $isKit): void
    {
        $this->isKit = $isKit;
    }

    public function getUnification(): array
    {
        return $this->unification ?? [];
    }

    public function getCatalogId(): ?string
    {
        return $this->catalogId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getVendorCode(): ?string
    {
        return $this->vendorCode;
    }

    public function getUnitId(): ?string
    {
        return $this->unitId;
    }

    public function getBrandId(): ?string
    {
        return $this->brandId;
    }

    public function getProductLineId(): ?string
    {
        return $this->productLineId;
    }

    public function isKit(): bool
    {
        return $this->isKit;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

}
