<?php

namespace App\Factory;

use App\Exception\SourceNotFound;
use Ozon\Application\Query\Attribute\GetByCatalog\Query as OzonCatalogGetAllQuery;
use Pim\Application\Query\Attribute\GetByCatalog\Query as PimCatalogGetAllQuery;
use Shared\Domain\Query\QueryInterface;
use Wildberries\Application\Query\Attribute\GetByCatalog\Query as WildberriesCatalogGetAllQuery;

readonly class AttributeQueryFactory
{
    /**
     * @throws SourceNotFound
     */
    public static function getQuery(string $source, string $catalogId): QueryInterface
    {
        return match ($source) {
            'pim' => new PimCatalogGetAllQuery(catalogId: $catalogId),
            'ozon' => new OzonCatalogGetAllQuery(catalogId: $catalogId),
            'wildberries' => new WildberriesCatalogGetAllQuery(catalogId: $catalogId),
            default => throw new SourceNotFound('Source not exist - AttributeQueryFactory')
        };
    }
}
