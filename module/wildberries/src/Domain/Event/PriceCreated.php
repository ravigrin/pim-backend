<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class PriceCreated implements EventInterface
{
    public function __construct(
        public string $productId,
        public int    $price,
        public int    $discount,
        public int    $finalPrice,
        public bool   $isStockAvailable,
        public ?float $netCost,
        public ?float $productionCost,
        public ?bool  $productionCostFlag
    )
    {
    }
}
