<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product\GetByUser;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Application\Query\Product\ProductDto;
use Wildberries\Domain\Repository\ProductInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductInterface $productRepository
    ) {
    }

    public function __invoke(Query $query): array
    {
        $products = $this->productRepository->findBy([]);
        $resp = [];
        foreach ($products as $product) {
            $resp[] = new ProductDto(
                productId: $product->getProductId(),
                name: $product->getTitle() ?? '',
                sku: $product->getVendorCode()
            );
        }
        return $resp;
    }
}
