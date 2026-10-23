<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class SizeCreated implements EventInterface
{
    public function __construct(
        public string $sizeId,
        public string $productId,
        public int    $chrtId,
        public string $techSize,
        public string $wbSize,
        /**
         * @var string[]
         */
        public array  $skus
    )
    {
    }
}
