<?php

declare(strict_types=1);

namespace Ozon\Application\Command\ProductExport;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public string $productId
    ) {
    }
}
