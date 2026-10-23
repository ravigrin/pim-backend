<?php

declare(strict_types=1);

namespace Fulfillment\Domain\Repository\Dto;

final readonly class OrderDataDto
{
    /**
     * @param list<array{sku: string, quantity: int}> $items
     */
    public function __construct(
        public string $orderId,
        public string $warehouse,
        public array $items,
    ) {}
}
