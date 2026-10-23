<?php

declare(strict_types=1);

namespace AdAnalytics\Application\Query\Statistics;

use AdAnalytics\Domain\Repository\AdAnalyticsProviderRegistry;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(private AdAnalyticsProviderRegistry $registry) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function __invoke(Query $query): array
    {
        $provider = $this->registry->get($query->source);

        return array_map(
            static fn ($dto): array => $dto->toArray(),
            $provider->getStatistics([
                'from' => $query->dateFrom,
                'to' => $query->dateTo,
            ]),
        );
    }
}
