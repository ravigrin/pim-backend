<?php

namespace Shared\Domain\Specification\QueryResponse;

interface ProductAttributeHistoryInterface
{
    /**
     * @return AttributeHistoryDto[]
     */
    public function getHistory(): array;

    public function getPagination(): PaginationDto;
}