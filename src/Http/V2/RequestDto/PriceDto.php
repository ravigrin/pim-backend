<?php

declare(strict_types=1);

namespace App\Http\V2\RequestDto;

/** @psalm-suppress MissingConstructor */
final class PriceDto
{
    public function __construct(
        public string $productId,
        public int    $price,
        public int    $discount,
        public int    $totalPrice,
        public float  $costPrice,
    ) {
    }
}
