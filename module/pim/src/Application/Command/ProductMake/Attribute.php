<?php

namespace Pim\Application\Command\ProductMake;

class Attribute
{
    /**
     * @param string $attributeId
     * @param string[]|string $value
     */
    public function __construct(
        public string       $attributeId,
        public string|array $value
    ) {
    }
}
