<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\PriceCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_price')]
#[ORM\HasLifecycleCallbacks]
class Price extends AggregateRoot
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::GUID)]
        private string $productId,
        #[ORM\Column]
        private int    $price,
        #[ORM\Column]
        private int    $discount,
        #[ORM\Column]
        private int    $finalPrice,
        #[ORM\Column]
        private bool   $isStockAvailable,
        #[ORM\Column(nullable: true)]
        private ?float $netCost = null,
        #[ORM\Column(nullable: true)]
        private ?float $productionCost = null,
        /**
         * @var bool Если true - цена производства была установлена руками, false - посчитана автоматически
         */
        #[ORM\Column(nullable: true)]
        private ?bool  $productionCostFlag = null
    )
    {
        $this->apply(new PriceCreated(
            productId: $this->productId,
            price: $this->price,
            discount: $this->discount,
            finalPrice: $this->finalPrice,
            isStockAvailable: $this->isStockAvailable,
            netCost: $this->netCost,
            productionCost: $this->productionCost,
            productionCostFlag: $this->productionCostFlag
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

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): void
    {
        $this->discount = $discount;
    }

    public function getFinalPrice(): int
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(int $finalPrice): void
    {
        $this->finalPrice = $finalPrice;
    }

    public function getNetCost(): ?float
    {
        return $this->netCost;
    }

    public function setNetCost(?float $netCost): void
    {
        $this->netCost = $netCost;
    }

    public function getProductionCost(): ?float
    {
        return $this->productionCost;
    }

    public function setProductionCost(?float $productionCost): void
    {
        $this->productionCost = $productionCost;
    }

    public function getProductionCostFlag(): ?bool
    {
        return $this->productionCostFlag;
    }

    public function setProductionCostFlag(?bool $productionCostFlag): void
    {
        $this->productionCostFlag = $productionCostFlag;
    }

    public function isStockAvailable(): bool
    {
        return $this->isStockAvailable;
    }

    public function setIsStockAvailable(bool $isStockAvailable): void
    {
        $this->isStockAvailable = $isStockAvailable;
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
