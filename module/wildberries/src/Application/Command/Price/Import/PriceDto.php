<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Price\Import;

/** @psalm-suppress MissingConstructor */
final class PriceDto
{
    public function __construct(
        public int  $nmId,
        public int  $price,
        public int  $discount,
        public int  $finalPrice,
        public bool $isStockAvailable
    ) {
    }
}
