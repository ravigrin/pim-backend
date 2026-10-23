<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Product\GetByUser;

use Ozon\Application\Query\Product\ProductDto;
use Ozon\Domain\Repository\ProductInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductInterface $productRepository,
    ) {
    }

    public function __invoke(Query $query): array
    {
        return array_map(
            fn (array $product): ProductDto => ProductDto::getDto($product),
            $this->productRepository->findProductsForFront()
        );
    }
}
