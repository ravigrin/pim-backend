<?php

declare(strict_types=1);

namespace Ozon\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;

#[ORM\Entity]
#[ORM\Table('ozon_product')]
#[ORM\HasLifecycleCallbacks]
class Product extends AggregateRoot
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $dateUpdate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $dateCreate;

    /**
     * @param string[] $unification
     */
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid $productUuid,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid $userUuid,
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string        $offerId,
        /**
         *
         */
        #[ORM\Column(length: 40, nullable: true)]
        private ?string       $barcode = null,
        /**
         *
         */
        #[ORM\Column(nullable: true)]
        private ?int          $ozonProductId = null,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private ?Uuid         $catalogUuid = null,
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string        $currencyCode = "RUB",
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string        $dimensionUnit = "mm",
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string        $vat = "0.2",
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string        $weightUnit = "g",
        /**
         * Список связанный товаров по uuid
         *
         */
        #[ORM\Column(length: 4000)]
        private array         $unification = [],
        /**
         *
         * Статусы:
         * 0 - новый/измененный
         * 1 - отправка
         * 2 - отправлено
         * 3 - ошибка
         */
        #[ORM\Column]
        private int           $export = 0,

        /**
         *
         */
        #[ORM\Column]
        private bool          $isDeleted = false,
    ) {
    }

    public function getProductUuid(): Uuid
    {
        return $this->productUuid;
    }

    public function getUserUuid(): Uuid
    {
        return $this->userUuid;
    }

    public function getOfferId(): string
    {
        return $this->offerId;
    }

    public function setOfferId(string $offerId): void
    {
        $this->offerId = $offerId;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getOzonProductId(): ?int
    {
        return $this->ozonProductId;
    }

    public function setOzonProductId(?int $ozonProductId): void
    {
        $this->ozonProductId = $ozonProductId;
    }

    public function getCatalogUuid(): ?Uuid
    {
        return $this->catalogUuid;
    }

    public function setCatalogUuid(?Uuid $catalogUuid): void
    {
        $this->catalogUuid = $catalogUuid;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getDimensionUnit(): string
    {
        return $this->dimensionUnit;
    }

    public function setDimensionUnit(string $dimensionUnit): void
    {
        $this->dimensionUnit = $dimensionUnit;
    }

    public function getVat(): string
    {
        return $this->vat;
    }

    public function setVat(string $vat): void
    {
        $this->vat = $vat;
    }

    public function getWeightUnit(): string
    {
        return $this->weightUnit;
    }

    public function setWeightUnit(string $weightUnit): void
    {
        $this->weightUnit = $weightUnit;
    }

    public function getUnification(): array
    {
        return $this->unification;
    }

    public function setUnification(array $unification): void
    {
        $this->unification = $unification;
    }

    public function getExport(): int
    {
        return $this->export;
    }

    public function setExport(int $export): void
    {
        $this->export = $export;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function getDateUpdate(): \DateTime
    {
        return $this->dateUpdate;
    }

    public function getDateCreate(): \DateTime
    {
        return $this->dateCreate;
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
}
