<?php

namespace App\Factory;

use App\Exception\SourceNotFound;
use Ozon\Application\Query\AttributeHistory\GetByProduct\Query as OzonAttributeHistoryQuery;
use Pim\Application\Query\AttributeHistory\GetByProduct\Query as PimAttributeHistoryQuery;
use Shared\Domain\Query\QueryInterface;
use Wildberries\Application\Query\AttributeHistory\GetByProduct\Query as WbHistoryQuery;

readonly class AttributeHistoryQueryFactory
{
    /**
     * @throws SourceNotFound
     */
    public static function getQuery(
        string $source,
        string $productId,
        int $page = 1,
        int $perPage = 30
    ): QueryInterface {
        $offset = 0;
        if($page > 1) {
            $offset = $perPage * ($page - 1);
        }

        return match ($source) {
            'pim' => new PimAttributeHistoryQuery(productId: $productId, offset: $offset, limit: $perPage, page: $page),
            'ozon' => new OzonAttributeHistoryQuery(productId: $productId, offset: $offset, limit: $perPage, page: $page),
            'wildberries' => new WbHistoryQuery(productId: $productId, offset: $offset, limit: $perPage, page: $page),
            default => throw new SourceNotFound('Source not exist - AttributeQueryFactory')
        };
    }
}
