<?php
declare(strict_types=1);

namespace Wildberries\Application\Query\Product;

/** @psalm-suppress MissingConstructor */
final class ProductPhotoItemDto
{
    public function __construct(
        public string $little,
        public string $small,
        public string $big
    )
    {
    }
}
