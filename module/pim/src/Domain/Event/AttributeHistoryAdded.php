<?php

namespace Pim\Domain\Event;

use Shared\Domain\Event\EventInterface;

class AttributeHistoryAdded implements EventInterface
{
    /**
     * @param string[] $value
     * @param string[]|null $oldValue
     */
    public function __construct(
        public string $productAttributeId,
        public string $attributeId,
        public string $productId,
        public string $userId,
        public array  $value,
        public ?array $oldValue = null,
    ) {
    }
}
