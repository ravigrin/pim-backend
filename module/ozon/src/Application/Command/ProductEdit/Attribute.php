<?php

namespace Ozon\Application\Command\ProductEdit;

class Attribute
{
    /**
     * @param string $attributeId
     * @param string[]|string $value
     */
    public function __construct(
        public string                      $attributeId,
        public bool|string|float|int|array $value
    ) {
    }
}
