<?php

declare(strict_types=1);

namespace Pim\Application\Query\Product\GetByUser;

use Pim\Application\Query\Product\ProductDto;
use Doctrine\DBAL\Exception;
use Pim\Domain\Repository\Pim\ProductInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductInterface $productRepository,
    ) {
    }

    /**
     * @return ProductDto[]
     * @throws Exception
     */
    public function __invoke(Query $query): array
    {
        return array_map(
            fn (array $product): ProductDto => ProductDto::getDto($product),
            $this->productRepository->findProductsForFront()
        );
    }
}
