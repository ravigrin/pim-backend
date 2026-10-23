<?php

declare(strict_types=1);

namespace Pim\Application\Query\Brand\GetById;

use Pim\Application\Query\Brand\BrandDto;
use Pim\Domain\Entity\Brand;
use Pim\Domain\Repository\Pim\BrandInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(private BrandInterface $brandRepository)
    {
    }

    public function __invoke(Query $query): ?BrandDto
    {
        $brand = $this->brandRepository->findOneByCriteria(
            ['brandId' => $query->brandId]
        );

        return ($brand instanceof Brand) ? BrandDto::getDto($brand) : null;
    }
}
