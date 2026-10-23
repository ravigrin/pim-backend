<?php

namespace Wildberries\Domain\Repository\Dto\Export;

use Wildberries\Domain\Repository\Dwh\Dto\WbProductSizesDto;

/**
 * DTO на обновление товара WB
 */
final class WbUpdateProductDto
{
    /**
     * @param int $nmId
     * @param string $vendorCode
     * @param string $brand
     * @param string $title
     * @param string $description
     * @param array $dimensions
     * @param array $characteristics
     * @param WbProductSizesDto[] $sizes
     */
    public function __construct(
        public int    $nmId,
        public string $vendorCode,
        public string $brand,
        public string $title,
        public string $description,
        public array  $dimensions,
        public array  $characteristics,
        public array  $sizes
    )
    {
    }
}
