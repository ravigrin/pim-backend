<?php

declare(strict_types=1);

namespace Ozon\Domain\Event;

use Shared\Domain\Event\EventInterface;
use Shared\Domain\ValueObject\Uuid;

class ProductAttributeHistoryAdded implements EventInterface
{
    public function __construct(
        public Uuid $productId,
        public Uuid $attributeId,
        public Uuid $catalogId,
    ) {
    }
}
