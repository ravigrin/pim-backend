<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class ProductCreated implements EventInterface
{
    public function __construct(
        public string  $productId,
        public ?int    $exportStatus = null,
        public ?string $catalogId = null,
        public ?int    $imtId = null,
        public ?int    $nmId = null,
        public ?string $vendorCode = null,
        public ?string $brand = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $sellerName = null,
        public ?string $nmUuid = null,
        public ?array  $dimensions = null,
        public ?array  $tags = null
    )
    {
    }
}
