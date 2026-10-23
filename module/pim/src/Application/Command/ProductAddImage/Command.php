<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductAddImage;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public string $productId,
        public array  $imagesId
    ) {
    }
}
