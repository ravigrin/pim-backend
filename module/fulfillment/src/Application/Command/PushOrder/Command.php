<?php

declare(strict_types=1);

namespace Fulfillment\Application\Command\PushOrder;

use Shared\Domain\Command\CommandInterface;

final readonly class Command implements CommandInterface
{
    public function __construct(
        public string $provider,
        public string $orderId,
        public string $warehouse,
        /** @var list<array{sku: string, quantity: int}> */
        public array $items,
    ) {}
}
