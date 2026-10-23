<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class ProductAttributeHistoryCreated implements EventInterface
{
    public function __construct(
        public string $productAttributeHistoryId,
        public string $userId,
        public string $productAttributeId,
        public array  $newValue,
        public ?array $oldValue = null,
    ) {
    }
}
