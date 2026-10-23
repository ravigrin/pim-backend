<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Attribute\GetByCatalog;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(public string $catalogId)
    {
    }
}
