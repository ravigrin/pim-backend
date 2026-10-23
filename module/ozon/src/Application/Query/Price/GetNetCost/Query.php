<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Price\GetNetCost;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public string $productId,
        public float  $price
    ) {
    }
}
