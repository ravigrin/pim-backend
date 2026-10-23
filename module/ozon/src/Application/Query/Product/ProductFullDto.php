<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Product;

/** @psalm-suppress MissingConstructor */
final class ProductFullDto
{
    public string $productId;

    public ?string $catalogId = null;

    public array $union;

    public array $attributes;

    public static function getDto(
        string  $productId,
        ?string $catalogId = null,
        array   $union = [],
        array   $attributes = []
    ): self {
        $result = new self();
        $result->productId = $productId;
        $result->catalogId = $catalogId;
        $result->union = $union;
        $result->attributes = $attributes;

        return $result;
    }
}
