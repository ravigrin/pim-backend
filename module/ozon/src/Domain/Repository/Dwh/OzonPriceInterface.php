<?php

namespace Ozon\Domain\Repository\Dwh;

interface OzonPriceInterface
{
    public function findCostPriceBy(string $vendorCode, float $sellingPrice): array;

    public function updatePrice(
        string $offerId,
        int    $productId,
        int    $minPrice,
        int    $oldPrice,
        int    $price
    ): void;
}
