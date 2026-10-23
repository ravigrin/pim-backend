<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository\Dwh;

use Ozon\Domain\Entity\Product;
use Ozon\Domain\Repository\Dwh\Dto\Product\Export\ProductDto;
use Ozon\Domain\Repository\Dwh\Dto\Product\Export\ProductsDto;

interface OzonExportInterface
{
    public function buildProduct(Product $product): ProductDto;

    public function send(ProductsDto $productsDto): void;

}
