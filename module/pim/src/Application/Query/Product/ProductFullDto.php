<?php

declare(strict_types=1);

namespace Pim\Application\Query\Product;

use Pim\Domain\Entity\Product;

/** @psalm-suppress MissingConstructor */
final class ProductFullDto
{
    public string $productId;

    public ?string $sku = null;

    public ?string $unitId = null;

    public ?string $brandId = null;

    public ?string $productLineId = null;

    public ?string $catalogId = null;

    public bool $isKit;

    public array $union;

    public ?int $ozonStatus = null;

    public ?int $wbStatus = null;

    public array $attributes;

    public static function getDto(Product $product, array $attributes, ?int $ozonStatus, ?int $wbStatus): self
    {
        $result = new self();
        $result->productId = $product->getProductId();
        $result->sku = $product->getVendorCode();
        $result->unitId = $product->getUnitId();
        $result->brandId = $product->getBrandId();
        $result->productLineId = $product->getProductLineId();
        $result->catalogId = $product->getCatalogId();
        $result->isKit = $product->isKit();
        $result->union = $product->getUnification();
        $result->ozonStatus = $ozonStatus;
        $result->wbStatus = $wbStatus;

        $result->attributes = $attributes;

        return $result;
    }
}
