<?php

namespace Wildberries\Domain\Repository\Dto;

final class WbCatalogDto
{
    public function __construct(
        public int    $categoryId,
        public int    $parentCategoryId,
        public string $categoryName,
        public string $parentCategoryName,
        public bool   $isVisible
    ) {
    }
}
