<?php

namespace App\Factory;

use App\Exception\SourceNotFound;
use Ozon\Application\Query\Product\GetByUser\Query as OzonGetByUserQuery;
use Pim\Application\Query\Product\GetByUser\Query as PimGetByUserQuery;
use Shared\Domain\Query\QueryInterface;
use Wildberries\Application\Query\Product\GetByUser\Query as WbGetByUserQuery;

readonly class ProductsQueryFactory
{
    /**
     * @throws SourceNotFound
     */
    public static function getQuery(string $source): QueryInterface
    {
        return match ($source) {
            'pim' => new PimGetByUserQuery(),
            'ozon' => new OzonGetByUserQuery(),
            'wildberries' => new WbGetByUserQuery(),
            default => throw new SourceNotFound('Source not exist - AttributeQueryFactory')
        };
    }
}
