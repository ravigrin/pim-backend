<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class ProductAttributeCreated implements EventInterface
{
    public function __construct(
        public string $productAttributeId,
        public string $productId,
        public string $attributeId,
        public array  $value,
        public string $hash,
        public ?int   $wbAttributeId = null
    )
    {
    }
}
