<?php

namespace App\Factory;

use App\Exception\SourceNotFound;
use Ozon\Application\Query\Price\GetNetCost\Query as OzonGetNetCostQuery;
use Ozon\Application\Query\Product\GetWithPrices\Query as OzonGetWithPricesQuery;
use Shared\Domain\Query\QueryInterface;
use Wildberries\Application\Query\Price\GetByUser\Query as WbGetByUserQuery;
use Wildberries\Application\Query\Price\GetNetCost\Query as WbGetNetCostQuery;

readonly class PriceQueryFactory
{
    /**
     * @throws SourceNotFound
     */
    public static function getQuery(string $source, string $username, ?string $productId = null): QueryInterface
    {
        return match ($source) {
            'ozon' => new OzonGetWithPricesQuery(),
            'wildberries' => new WbGetByUserQuery(username: $username, productId: $productId),
            default => throw new SourceNotFound('Source not exist - PriceQueryFactory::getQuery()')
        };
    }

    /**
     * @throws SourceNotFound
     */
    public static function getNetCostQuery(string $source, string $username, string $productId, int $finalPrice): QueryInterface
    {
        return match ($source) {
            'ozon' => new OzonGetNetCostQuery(productId: $productId, price: $finalPrice),
            'wildberries' => new WbGetNetCostQuery(username: $username, productId: $productId, finalPrice: $finalPrice),
            default => throw new SourceNotFound('Source not exist - PriceQueryFactory::getNetCostQuery()')
        };
    }
}
