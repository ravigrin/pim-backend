<?php

declare(strict_types=1);

namespace Files\Application\Query\GetByProductId;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(public string $productId)
    {
    }
}
