<?php

namespace Shared\Domain\Specification\QueryResponse;

final class PaginationDto
{
    /**
     * @param int $rowsCount Всего строк
     * @param int $pageCount Всего страниц
     * @param int $page Текущая страница
     * @param int $perPage Колличество на странице
     */
    public function __construct(
        public int $rowsCount,
        public int $pageCount,
        public int $page,
        public int $perPage
    ) {
    }
}
