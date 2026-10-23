<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class SuggestCreated implements EventInterface
{
    public function __construct(
        public string  $suggestId,
        public array   $value,
        public ?string $attributeId = null,
        public ?string $catalogId = null,
        public ?int    $objectId = null,
        public ?string $info = null
    ) {
    }
}
