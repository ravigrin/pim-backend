<?php

namespace App\Factory;

use App\Exception\SourceNotFound;
use Ozon\Application\Query\Dictionary\GetByCatalogAttribute\Query as OzonGetByAttributeQuery;
use Pim\Application\Query\Dictionary\GetByAttribute\Query as PimGetByAttributeQuery;
use Shared\Domain\Query\QueryInterface;
use Wildberries\Application\Query\Suggest\GetByAttribute\Query as WbGetByAttributeQuery;

readonly class DictionaryQueryFactory
{
    /**
     * @throws SourceNotFound
     */
    public static function getQuery(string $source, string $attributeId, ?string $catalogId): QueryInterface
    {
        return match ($source) {
            'pim' => new PimGetByAttributeQuery(attributeId: $attributeId),
            'ozon' => new OzonGetByAttributeQuery(catalogId: $catalogId, attributeId: $attributeId),
            'wildberries' => new WbGetByAttributeQuery(attributeId: $attributeId),
            default => throw new SourceNotFound('Source not exist - DictionaryQueryFactory')
        };
    }
}
