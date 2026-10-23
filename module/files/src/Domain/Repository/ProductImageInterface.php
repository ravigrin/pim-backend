<?php

namespace Files\Domain\Repository;

use Files\Domain\Entity\ProductImage;

interface ProductImageInterface
{
    /**
     * @return ProductImage[]
     */
    public function findByProductId(string $productId): array;

    public function findByImageId(string $imageId): ?ProductImage;
}
