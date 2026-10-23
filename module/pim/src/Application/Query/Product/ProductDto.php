<?php

declare(strict_types=1);

namespace Pim\Application\Query\Product;

/** @psalm-suppress MissingConstructor */
final class ProductDto
{
    public string $productId;

    public ?string $sku = null;

    public ?string $unitName = null;

    public ?string $brandName = null;

    public ?string $productLineName = null;

    public ?string $name = null;

    public ?string $status = null;

    /**
     * @param array{
     *      productId: string,
     *      vendorCode: string,
     *      productName: string|null,
     *      unitName: string,
     *      brandName: string,
     *      productLineName: string|null,
     *      productStatus: string|null
     *  } $product
     * @return self
     */
    public static function getDto(array $product): self
    {
        $result = new self();
        $result->productId = $product["productId"];
        $result->sku = $product["vendorCode"];
        $result->unitName = $product["unitName"];
        $result->brandName = $product["brandName"];
        $result->productLineName = $product["productLineName"] ?? null;
        $result->name = $product["productName"];
        $result->status = $product["productStatus"];

        return $result;
    }
}
