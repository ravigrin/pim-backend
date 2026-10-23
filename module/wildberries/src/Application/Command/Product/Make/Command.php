<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Product\Make;

use Pim\Domain\Entity\User;
use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public User   $user,
        public string $productId,
        public string $catalogId,
        public array  $union,
        public array  $attributes
    ) {
    }
}
