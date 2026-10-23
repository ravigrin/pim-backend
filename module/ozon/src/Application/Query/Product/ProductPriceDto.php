<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Product;

/** @psalm-suppress MissingConstructor */
final class ProductPriceDto
{
    public string $productId;

    public ?string $productName;

    public ?string $vendorCode;

    public ?string $barcode;

    public int $price;

    public ?int $discount;

    public int $totalPrice;

    public ?float $costPrice;

    public ?float $productionPrice;

    public ?bool $productionPriceFlag = null;

    /**
     * @param array{
     *     productId: string,
     *     name: string,
     *     vendorCode: string,
     *     barcode: string,
     *     price: string,
     *     salePercent: string,
     *     oldPrice: string,
     *     costPrice: string,
     *     productionPrice: string,
     *     productionPriceFlag: bool
     * } $product
     * @return self
     */
    public static function getDto(array $product): self
    {
        $result = new self();
        $result->productId = $product['productId'];
        $result->productName = $product['name'];
        $result->vendorCode = $product['vendorCode'];
        $result->barcode = $product['barcode'];
        $result->price = (int)$product['oldPrice'];
        $result->discount = (int)$product['salePercent'];
        $result->totalPrice = (int)$product['price'];
        $result->costPrice = (float)$product['costPrice'];
        $result->productionPrice = (float)$product['productionPrice'];
        $result->productionPriceFlag = isset($product['productionPriceFlag']) ? filter_var($product['productionPriceFlag'], FILTER_VALIDATE_BOOLEAN) : null;
        return $result;
    }
}
