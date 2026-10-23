<?php

namespace Ozon\Application\Query\AttributeHistory;

use Shared\Domain\Specification\QueryResponse\AttributeHistoryDto;
use Shared\Domain\Specification\QueryResponse\PaginationDto;
use Shared\Domain\Specification\QueryResponse\ProductAttributeHistoryInterface;

readonly class ProductAttributeHistoryResponse implements ProductAttributeHistoryInterface
{
    public function __construct(
        private array         $history,
        private PaginationDto $pagination
    )
    {
    }

    /**
     * @return AttributeHistoryDto[]
     */
    public function getHistory(): array
    {
        return $this->history;
    }

    public function getPagination(): PaginationDto
    {
        return $this->pagination;
    }
}
