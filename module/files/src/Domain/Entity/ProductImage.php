<?php

declare(strict_types=1);

namespace Files\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

/**
 *
 */
#[ORM\Entity]
#[ORM\Table('files_product_image')]
#[ORM\HasLifecycleCallbacks]
class ProductImage extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(unique: true)]
    private int $productImageId;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $dateCreate;

    public function __construct(
        /**
         *  id pim товара
         */
        #[ORM\Column]
        private string $productId,
        /**
         *  id картинки
         */
        #[ORM\Column(length: 1000)]
        private string $imageId,
        /**
         * Статус для удаления
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function getProductImageId(): int
    {
        return $this->productImageId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getImageId(): string
    {
        return $this->imageId;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function getDateCreate(): \DateTime
    {
        return $this->dateCreate;
    }

    #[ORM\PrePersist]
    public function setDateCreate(): void
    {
        $this->dateCreate = new \DateTime();
    }

}
