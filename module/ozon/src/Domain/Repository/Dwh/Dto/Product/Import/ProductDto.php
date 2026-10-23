<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository\Dwh\Dto\Product\Import;

final class ProductDto
{
    /**
     * @param string[] $images
     * @param string[] $images360
     * @param AttributeDto[] $attributes
     */
    public function __construct(
        public string $clientId,
        public string $productId,
        public string $offerId,
        public string $name,
        public string $barcode,
        public string $descriptionCategoryId,
        public string $typeId,
        public array  $images,
        public string $oldPrice,
        public string $premiumPrice,
        public string $price,
        public string $vat,
        public array  $images360,
        public string $colorImage,
        public string $primaryImage,
        public string $currencyCode,
        public string $height,
        public string $depth,
        public string $width,
        public string $dimensionUnit,
        public string $weight,
        public string $weightUnit,
        public string $pdfList,
        public array  $attributes,
        public array  $complexAttributes,
    ) {
    }
}
