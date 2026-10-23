<?php

declare(strict_types=1);

namespace Pim\Application\Query\Brand\GetNameByProduct;

use Pim\Domain\Repository\Pim\BrandInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(private BrandInterface $brandRepository)
    {
    }

    public function __invoke(Query $query): ?string
    {
        $brand = $this->brandRepository->findNameByProduct($query->productId);
        return $brand?->getName();
    }
}
