<?php

namespace App\Factory;

use App\Exception\SourceNotFound;
use Ozon\Application\Query\Catalog\GetAll\Query as OzonCatalogGetAllQuery;
use Pim\Application\Query\Catalog\GetAll\Query as PimCatalogGetAllQuery;
use Shared\Domain\Query\QueryInterface;
use Wildberries\Application\Query\Catalog\GetAll\Query as WildberriesCatalogGetAllQuery;

readonly class CatalogQueryFactory
{
    /**
     * @throws SourceNotFound
     */
    public static function getQuery(string $source): QueryInterface
    {
        return match ($source) {
            'pim' => new PimCatalogGetAllQuery(),
            'ozon' => new OzonCatalogGetAllQuery(),
            'wildberries' => new WildberriesCatalogGetAllQuery(),
            default => throw new SourceNotFound('Source not exist - CatalogQueryFactory')
        };
    }

}
