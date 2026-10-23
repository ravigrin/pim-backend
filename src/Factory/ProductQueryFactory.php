<?php

namespace App\Factory;

use App\Exception\SourceNotFound;
use Ozon\Application\Query\Product\GetByUuid\Query as OzonGetByIdQuery;
use Pim\Application\Query\Product\GetByUuid\Query as PimGetByIdQuery;
use Shared\Domain\Query\QueryInterface;
use Wildberries\Application\Query\Product\GetByUuid\Query as WildberriesGetByIdQuery;

readonly class ProductQueryFactory
{
    /**
     * @throws SourceNotFound
     */
    public static function getQuery(string $source, string $productId): QueryInterface
    {
        return match ($source) {
            'pim' => new PimGetByIdQuery(productId: $productId),
            'ozon' => new OzonGetByIdQuery(productId: $productId),
            'wildberries' => new WildberriesGetByIdQuery(productId: $productId),
            default => throw new SourceNotFound('Source not exist - AttributeQueryFactory')
        };
    }
}
