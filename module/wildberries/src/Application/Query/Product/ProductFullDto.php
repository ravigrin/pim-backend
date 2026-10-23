<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product;

use Wildberries\Domain\Entity\Media;
use Wildberries\Domain\Entity\Product;

/** @psalm-suppress MissingConstructor */
final class ProductFullDto
{
    /**
     * @param string $productId
     * @param string|null $catalogId
     * @param array{attributeId: string, value: int|string|array}|null $attributes
     * @param ProductMediaDto|null $media
     * @param string[]|null $union
     */
    public function __construct(
        public string           $productId,
        public ?string          $catalogId = null,
        public ?array           $attributes = null,
        public ?ProductMediaDto $media = null,
        public ?array           $union = []
    )
    {
    }

    /**
     * @param Product $product
     * @param Media[] $medias
     * @param array{attributeId: string, value: int|string|array} $attributes
     * @param string[]|null $unions
     * @return self
     */
    public static function getDto(
        Product $product,
        array   $attributes,
        array   $medias,
        ?array  $unions = null
    ): self
    {
        return new self(
            productId: $product->getProductId(),
            catalogId: $product->getCatalogId(),
            attributes: $attributes,
            media: ProductMediaDto::getDto($medias),
            union: $unions
        );
    }
}
