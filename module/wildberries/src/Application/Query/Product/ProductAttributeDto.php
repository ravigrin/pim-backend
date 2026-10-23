<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product;

use DateTime;

/** @psalm-suppress MissingConstructor */
final class ProductAttributeDto
{
    /**
     * @param string $attributeId
     * @param int|string|array|DateTime|null $value
     */
    public function __construct(
        public string                         $attributeId,
        public int|string|array|DateTime|null $value = null
    )
    {
    }
}
