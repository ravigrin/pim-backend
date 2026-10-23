<?php

declare(strict_types=1);

namespace AdAnalytics\Domain\Repository;

use App\Exception\NotFoundException;

/**
 * Реестр подключённых адаптеров аналитики: source => provider.
 */
final class AdAnalyticsProviderRegistry
{
    /** @var array<string, AdAnalyticsProviderInterface> */
    private array $providers = [];

    public function register(AdAnalyticsProviderInterface $provider): void
    {
        $this->providers[$provider->name()] = $provider;
    }

    public function get(string $source): AdAnalyticsProviderInterface
    {
        return $this->providers[$source]
            ?? throw new NotFoundException("Ad analytics provider '$source' is not registered");
    }

    /** @return string[] */
    public function sources(): array
    {
        return array_keys($this->providers);
    }
}
