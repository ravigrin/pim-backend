<?php

declare(strict_types=1);

namespace AdAnalytics\Infrastructure\Http;

use AdAnalytics\Application\Query\Statistics\Dto\StatisticsDto;
use AdAnalytics\Domain\Repository\AdAnalyticsProviderInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * Универсальный REST-адаптер рекламной аналитики.
 *
 * Провайдер описывается конфигурацией (base uri, токен, имя источника),
 * поэтому подключение нового вендора не требует нового класса — только
 * новый сервис в services.yaml. При нестандартном контракте конкретный
 * вендор добавляется как своя реализация AdAnalyticsProviderInterface.
 */
final readonly class GenericRestProvider implements AdAnalyticsProviderInterface
{
    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private string $name,
        private string $baseUrl,
        private string $token,
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param array<string, mixed> $criteria
     *
     * @return StatisticsDto[]
     */
    public function getStatistics(array $criteria): array
    {
        $uri = sprintf(
            '%s/statistics?from=%s&to=%s',
            rtrim($this->baseUrl, '/'),
            urlencode((string) $criteria['from']),
            urlencode((string) $criteria['to']),
        );

        $request = $this->requestFactory
            ->createRequest('GET', $uri)
            ->withHeader('Authorization', "Bearer {$this->token}")
            ->withHeader('Accept', 'application/json');

        $response = $this->client->sendRequest($request);
        $payload = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return array_map(
            static fn (array $row): StatisticsDto => new StatisticsDto(
                source: (string) ($row['source'] ?? ''),
                externalId: (string) ($row['id'] ?? ''),
                date: (string) ($row['date'] ?? ''),
                views: (int) ($row['views'] ?? 0),
                clicks: (int) ($row['clicks'] ?? 0),
                cost: (float) ($row['cost'] ?? 0),
                orders: (int) ($row['orders'] ?? 0),
                revenue: (float) ($row['revenue'] ?? 0),
            ),
            $payload['items'] ?? [],
        );
    }
}
