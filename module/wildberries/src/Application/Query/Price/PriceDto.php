<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Price;

/** @psalm-suppress MissingConstructor */
final class PriceDto
{
    public function __construct(
        public string  $productId,
        public ?string $image = null,
        public ?string $brand = null,
        public ?string $object = null,
        public ?string $articleWb = null,
        public ?string $articlePim = null,
        public ?int    $price = null,
        public ?int    $discount = null,
        public ?int    $totalPrice = null,
        public ?float  $costPrice = null,
        public ?float  $productionPrice = null,
        public ?bool   $productionPriceFlag = null
    )
    {
    }
}
