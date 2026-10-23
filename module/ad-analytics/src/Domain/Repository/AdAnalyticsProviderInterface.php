<?php

declare(strict_types=1);

namespace AdAnalytics\Domain\Repository;

use AdAnalytics\Application\Query\Statistics\Dto\StatisticsDto;

/**
 * Порт адаптера рекламной аналитики.
 *
 * Реализация выбирается по имени провайдера (registry) — конкретный вендор
 * не должен просачиваться выше Infrastructure.
 */
interface AdAnalyticsProviderInterface
{
    /** Имя провайдера, например "provider-a". */
    public function name(): string;

    /**
     * Выгрузка статистики по ссылкам/кампаниям за период.
     *
     * @param array<string, mixed> $criteria {from, to, campaignId?}
     *
     * @return StatisticsDto[]
     */
    public function getStatistics(array $criteria): array;
}
