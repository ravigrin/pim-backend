<?php

declare(strict_types=1);

namespace Pim\Application\Query\Product\GetVendorCode;

use Pim\Domain\Service\GenerateSku;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private GenerateSku $generateSku,
    )
    {
    }

    public function __invoke(Query $query): ?string
    {
        return $this->generateSku->build(
            unit: $query->unit,
            brand: $query->brand,
            productLine: $query->productLine,
            isKit: $query->isKit
        );
    }
}
