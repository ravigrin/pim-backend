<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Product;

/** @psalm-suppress MissingConstructor */
final class ProductDto
{
    public string $productId;

    public ?string $sku = null;

    public ?string $name;

    public static function getDto(array $product): self
    {
        $result = new self();
        $result->productId = $product["productId"];
        $result->sku = $product["vendorCode"];
        $result->name = $product["productName"];
        return $result;
    }
}
