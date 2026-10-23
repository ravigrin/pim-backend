<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Price\Export;

/** @psalm-suppress MissingConstructor */
final class PriceDto
{
    public function __construct(
        public int $nmId,
        public int $price
    )
    {
    }
}
