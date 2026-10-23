<?php

declare(strict_types=1);

namespace Fulfillment\Domain\Repository\Dto;

final readonly class PushResultDto
{
    public function __construct(
        public string $orderId,
        public string $externalOrderId,
        public bool $success,
        public ?string $message = null,
    ) {}
}
