<?php

declare(strict_types=1);

namespace Pim\Application\Query\ProductLine\GetById;

use Pim\Application\Query\ProductLine\ProductLineDto;
use Pim\Domain\Entity\ProductLine;
use Pim\Domain\Repository\Pim\ProductLineInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductLineInterface $productLineRepository
    )
    {
    }

    public function __invoke(Query $query): ?ProductLineDto
    {
        $productLine = $this->productLineRepository->findOneByCriteria([
            'productLineId' => $query->productLineId
        ]);
        return ($productLine instanceof ProductLine) ? ProductLineDto::getDto($productLine) : null;
    }
}
