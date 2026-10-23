<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class AttributeMapCreated implements EventInterface
{
    public function __construct(
        public string  $attributeMapId,
        public string  $wbAttributeId,
        public string  $pimAttributeId,
        public string  $wbName,
        public string  $pimAlias,
        public ?string $wbMeasure = null,
        public ?string $pimMeasure = null
    ) {
    }
}
