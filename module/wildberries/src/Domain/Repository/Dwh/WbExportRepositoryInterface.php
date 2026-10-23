<?php

namespace Wildberries\Domain\Repository\Dwh;

use Wildberries\Application\Command\Price\Export\PriceDto;
use Wildberries\Domain\Repository\Dto\Export\WbCreateProductDto;
use Wildberries\Domain\Repository\Dto\Export\WbDivideProductDto;
use Wildberries\Domain\Repository\Dto\Export\WbUnionProductDto;
use Wildberries\Domain\Repository\Dto\Export\WbUpdateProductDto;

interface WbExportRepositoryInterface
{
    /**
     * @param WbCreateProductDto[] $productsDto
     */
    public function create(array $productsDto, string $seller): void;

    /**
     * @param WbUpdateProductDto[] $productsDto
     */
    public function update(array $productsDto, string $seller): void;

    public function unionOrDivide(WbUnionProductDto|WbDivideProductDto $productsDto, string $seller): void;

    /**
     * @param PriceDto[] $prices
     * @param string $seller
     * @return void
     */
    public function priceUpdate(array $prices, string $seller): void;
}
