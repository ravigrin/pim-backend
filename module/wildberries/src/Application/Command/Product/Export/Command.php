<?php

namespace Wildberries\Application\Command\Product\Export;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public ?string $productId = null
    ) {
    }
}
