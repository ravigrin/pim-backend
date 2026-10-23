<?php

namespace Pim\Domain\Event;

use Shared\Domain\Event\EventInterface;

class ProductAttributeAdded implements EventInterface
{
    /**
     * @param string[]|int[]|float[] $value
     */
    public function __construct(
        public string $productId,
        public string $attributeId,
        public string $userId,
        public array  $value,
    ) {
    }
}
