<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product;

use Wildberries\Domain\Entity\Product;

/** @psalm-suppress MissingConstructor */
final class ProductDto
{
    public function __construct(
        public string  $productId,
        public string   $name,
        public ?string $sku = null,
    ) {
    }

    public static function getDto(Product $product): self
    {
        return new self(
            productId: $product->getProductId(),
            name: '',
            sku: $product->getVendorCode()
        );
    }
}
