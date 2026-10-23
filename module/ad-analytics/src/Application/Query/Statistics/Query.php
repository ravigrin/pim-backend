<?php

declare(strict_types=1);

namespace AdAnalytics\Application\Query\Statistics;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public readonly string $source,
        public readonly string $dateFrom,
        public readonly string $dateTo,
    ) {}
}
