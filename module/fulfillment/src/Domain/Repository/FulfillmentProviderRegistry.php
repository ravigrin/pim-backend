<?php

declare(strict_types=1);

namespace Fulfillment\Domain\Repository;

use App\Exception\NotFoundException;

/**
 * Реестр подключённых фулфилмент-операторов: name => provider.
 */
final class FulfillmentProviderRegistry
{
    /** @var array<string, FulfillmentProviderInterface> */
    private array $providers = [];

    public function register(FulfillmentProviderInterface $provider): void
    {
        $this->providers[$provider->name()] = $provider;
    }

    public function get(string $name): FulfillmentProviderInterface
    {
        return $this->providers[$name]
            ?? throw new NotFoundException("Fulfillment provider '$name' is not registered");
    }

    /** @return string[] */
    public function names(): array
    {
        return array_keys($this->providers);
    }
}
