<?php

namespace App\Factory;

use App\Exception\SourceNotFound;
use Ozon\Application\Query\AttributeGroup\GetAll\Query as OzonAttributeGroupQuery;
use Pim\Application\Query\AttributeGroup\GetAll\Query as PimAttributeGroupQuery;
use Shared\Domain\Query\QueryInterface;
use Wildberries\Application\Query\AttributeGroup\GetAll\Query as WbAttributeGroupQuery;

readonly class AttributeGroupQueryFactory
{
    /**
     * @throws SourceNotFound
     */
    public static function getQuery(string $source, string $catalogId): QueryInterface
    {
        return match ($source) {
            'pim' => new PimAttributeGroupQuery(),
            'ozon' => new OzonAttributeGroupQuery($catalogId),
            'wildberries' => new WbAttributeGroupQuery(),
            default => throw new SourceNotFound('Source not exist - AttributeQueryFactory')
        };
    }
}
