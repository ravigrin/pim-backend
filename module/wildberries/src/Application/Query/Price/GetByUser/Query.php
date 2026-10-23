<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Price\GetByUser;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public string  $username,
        public ?string $productId = null
    ) {
    }
}
