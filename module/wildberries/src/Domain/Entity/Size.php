<?php
declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\SizeCreated;

/**
 * Размеры номенклатуры товара WB
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_size')]
#[ORM\HasLifecycleCallbacks]
class Size extends AggregateRoot
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private string $sizeId,
        #[ORM\Column(type: 'uuid')]
        private string $productId,
        /**
         * @var int Числовой идентификатор размера для данного артикула WB
         */
        #[ORM\Column]
        private int    $chrtId,
        /**
         * @var string Размер товара (А, XXL, 57 и др.)
         */
        #[ORM\Column(length: 64)]
        private string $techSize,
        /**
         * @var string Российский размер товара
         */
        #[ORM\Column(length: 64)]
        private string $wbSize,
        /**
         * @var string[] Баркоды/ШК товара
         */
        #[ORM\Column(type: Types::JSON)]
        private array  $skus
    )
    {
        $this->apply(new SizeCreated(
            sizeId: $sizeId,
            productId: $productId,
            chrtId: $chrtId,
            techSize: $techSize,
            wbSize: $wbSize,
            skus: $skus
        ));
    }

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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getSizeId(): string
    {
        return $this->sizeId;
    }

    public function setSizeId(string $sizeId): void
    {
        $this->sizeId = $sizeId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    public function getChrtId(): int
    {
        return $this->chrtId;
    }

    public function setChrtId(int $chrtId): void
    {
        $this->chrtId = $chrtId;
    }

    public function getTechSize(): string
    {
        return $this->techSize;
    }

    public function setTechSize(string $techSize): void
    {
        $this->techSize = $techSize;
    }

    public function getWbSize(): string
    {
        return $this->wbSize;
    }

    public function setWbSize(string $wbSize): void
    {
        $this->wbSize = $wbSize;
    }

    public function getSkus(): array
    {
        return $this->skus;
    }

    public function setSkus(array $skus): void
    {
        $this->skus = $skus;
    }
}
