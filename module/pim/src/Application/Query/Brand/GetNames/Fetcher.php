<?php

declare(strict_types=1);

namespace Pim\Application\Query\Brand\GetNames;

use Pim\Domain\Repository\Pim\BrandInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(private BrandInterface $brandRepository)
    {
    }

    public function __invoke(Query $query): array
    {
        $brands = $this->brandRepository->findByCriteria([]);
        $result = [];
        foreach ($brands as $brand) {
            $result[$brand->getBrandId()] = $brand->getName();
        }
        return $result;
    }
}
