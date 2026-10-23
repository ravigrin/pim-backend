<?php

namespace Pim\Domain\Event;

use Shared\Domain\Event\EventInterface;

class BrandPushed implements EventInterface
{
    public function __construct(
        public string $brandId,
        public string $brandName,
    ) {
    }
}
