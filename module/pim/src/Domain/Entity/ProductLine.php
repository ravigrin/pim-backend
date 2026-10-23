<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

/**
 * Свойство товара: Продуктоваря линейка. Используется менеджерами для группировки товаров
 * Привязана к бренду.
 */
#[ORM\Entity]
#[ORM\Table('pim_product_line')]
class ProductLine extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(length: 40, unique: true)]
        private string $productLineId,
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string $brandId,
        /**
         *
         */
        #[ORM\Column(length: 256)]
        private string $name,
        /**
         *
         */
        #[ORM\Column(length: 256)]
        private string $code,
        /**
         *
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getProductLineId(): string
    {
        return $this->productLineId;
    }

    public function getBrandId(): string
    {
        return $this->brandId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


}
