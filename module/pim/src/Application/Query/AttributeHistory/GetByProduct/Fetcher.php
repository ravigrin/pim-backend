<?php

declare(strict_types=1);

namespace Pim\Application\Query\AttributeHistory\GetByProduct;

use Pim\Application\Query\AttributeHistory\ProductAttributeHistoryResponse;
use Pim\Domain\Entity\ProductAttributeHistory;
use Pim\Domain\Repository\Pim\ProductAttributeHistoryInterface;
use Shared\Domain\Query\QueryHandlerInterface;
use Shared\Domain\Specification\QueryResponse\AttributeHistoryDto;
use Shared\Domain\Specification\QueryResponse\PaginationDto;

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
            fn(ProductAttributeHistory $historyData): AttributeHistoryDto => new AttributeHistoryDto(
                userId: $historyData->getUserId(),
                date: $historyData->getDateCreate(),
                attributeId: $historyData->getAttributeId(),
                oldValue: $historyData->getOldValue(),
                newValue: $historyData->getNewValue()
            ),
            $this->productAttributeHistory->findByCriteria(
                ["productId" => $query->productId],
                orderBy: ['dateCreate' => 'DESC'],
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
