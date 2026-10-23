<?php

namespace Wildberries\Domain\Repository\Dwh\Dto;

final class WbProductDto
{
    /**
     * @param WbProductAttributeDto $attributes
     * @param WbProductDimensionsDto $dimensions
     * @param WbProductSizesDto[] $sizes
     * @param WbProductMediaDto[] $media
     * @param WbProductCharacteristicsDto[] $characteristics
     */
    public function __construct(
        public WbProductAttributeDto  $attributes,
        public WbProductDimensionsDto $dimensions,
        public array                  $sizes,
        public array                  $media,
        public array                  $characteristics
    )
    {
    }
}
