<?php

namespace Ozon\Domain\Repository\Dwh\Dto\Product\Export;

final class ProductsDto
{
    /**
     * @param ProductDto[] $items
     */
    public function __construct(
        public array $items
    ) {
    }

}
