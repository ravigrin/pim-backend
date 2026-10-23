<?php

namespace Ozon\Domain\Repository\Dwh\Dto\Product\Export;

final class ProductDto
{
    /**
     * @param array[] $attributes
     * @param string[] $complex_attributes
     * @param string[] $images
     * @param string[] $images360
     * @param string[] $pdf_list
     */
    public function __construct(
        public array $attributes,
        public string $barcode,
        public int $description_category_id,
        public string $color_image,
        public array $complex_attributes,
        public string $currency_code,
        public int $depth,
        public string $dimension_unit,
        public int $height,
        public array $images,
        public array $images360,
        public string $name,
        public string $offer_id,
        public string $old_price,
        public array $pdf_list,
        public string $premium_price,
        public string $price,
        public string $primary_image,
        public string $vat,
        public int $weight,
        public string $weight_unit,
        public int $width
    ) {
    }
}
