<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class AttributeGroupCreated implements EventInterface
{
    public function __construct(
        public string  $attributeGroupId,
        public string  $name,
        public string  $alias,
        public string  $type,
        public int     $orderGroup,
        public ?string $tabId = null
    ) {
    }
}
