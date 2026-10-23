<?php

namespace Ozon\Domain\Repository\Ozon;

use Ozon\Domain\Repository\Dwh\Dto\Product\Export\ProductsDto;

interface ProductExportInterface
{
    public function send(ProductsDto $productsDto): void;

}
