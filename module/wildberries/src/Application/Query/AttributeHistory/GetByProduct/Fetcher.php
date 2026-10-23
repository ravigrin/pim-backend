<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\AttributeHistory\GetByProduct;

use Shared\Domain\Query\QueryHandlerInterface;
use Shared\Domain\Specification\QueryResponse\PaginationDto;
use Shared\Domain\Specification\QueryResponse\AttributeHistoryDto;
use Wildberries\Application\Query\AttributeHistory\ProductAttributeHistoryResponse;
use Wildberries\Domain\Repository\ProductAttributeHistoryInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductAttributeHistoryInterface $productAttributeHistory
    )
    {
    }

    /**
     * @param Query $query
     * @return ProductAttributeHistoryResponse
     */
    public function __invoke(Query $query): ProductAttributeHistoryResponse
    {
        $rowsCount = $this->productAttributeHistory->getRowsCount($query->productId);
        $pageCount = ceil($rowsCount / $query->limit);

        $pagination = new PaginationDto(
            rowsCount: $rowsCount,
            pageCount: (int)$pageCount,
            page: $query->page,
            perPage: $query->limit
        );

        $history = array_map(
            fn(array $historyData): AttributeHistoryDto => AttributeHistoryDto::getDto($historyData),
            $this->productAttributeHistory->findByProductId(
                productId: $query->productId,
                limit: $query->limit,
                offset: $query->offset
            )
        );

        return new ProductAttributeHistoryResponse(
            history: $history,
            pagination: $pagination
        );
    }
}
