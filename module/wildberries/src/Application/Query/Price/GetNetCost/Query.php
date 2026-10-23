<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Price\GetNetCost;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public string $username,
        public string $productId,
        public int    $finalPrice
    ) {
    }
}
