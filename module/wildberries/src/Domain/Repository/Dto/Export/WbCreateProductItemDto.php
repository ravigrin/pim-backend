<?php

namespace Wildberries\Domain\Repository\Dto\Export;

use Wildberries\Domain\Repository\Dwh\Dto\WbProductSizesDto;

final class WbCreateProductItemDto
{
    /**
     * @param CreateCharacteristicDto[] $characteristics
     * @param WbProductSizesDto[] $sizes
     */
    public function __construct(
        public int                    $nmId,
        public string                 $vendorCode,
        public string                 $brand,
        public string                 $title,
        public string                 $description,
        public WbProductDimensionsDto $dimensions,
        public array                  $characteristics,
        public array                  $sizes
    )
    {
    }
}