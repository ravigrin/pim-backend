<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class CatalogCreated implements EventInterface
{
    public function __construct(
        public string   $catalogId,
        public int    $objectId,
        public string $name,
        public ?int   $level = null,
        public ?int   $parentId = null,
        public ?bool  $isVisible = null
    ) {
    }
}
