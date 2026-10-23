<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class AttributeCreated implements EventInterface
{
    public function __construct(
        public string  $attributeId,
        public string  $name,
        public string  $type,
        public int     $charcType,
        public int     $maxCount,
        public string  $source,
        public bool    $isRequired,
        public bool    $isPopular,
        public bool    $isDictionary,
        public bool    $isReadOnly,
        public ?string $groupId = null,
        public ?string $alias = null,
        public ?string $measurement = null,
        public ?string $description = null,
        public ?bool   $isVisible = true
    )
    {
    }
}
