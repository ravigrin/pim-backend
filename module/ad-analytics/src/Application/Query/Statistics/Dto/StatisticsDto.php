<?php

declare(strict_types=1);

namespace AdAnalytics\Application\Query\Statistics\Dto;

/**
 * Срез статистики по рекламной кампании/ссылке за день.
 */
final class StatisticsDto
{
    public function __construct(
        public readonly string $source,
        public readonly string $externalId,
        public readonly string $date,
        public readonly int $views,
        public readonly int $clicks,
        public readonly float $cost,
        public readonly int $orders,
        public readonly float $revenue,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'externalId' => $this->externalId,
            'date' => $this->date,
            'views' => $this->views,
            'clicks' => $this->clicks,
            'cost' => $this->cost,
            'orders' => $this->orders,
            'revenue' => $this->revenue,
        ];
    }
}
