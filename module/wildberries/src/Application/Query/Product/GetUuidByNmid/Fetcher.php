<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product\GetUuidByNmid;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Domain\Repository\ProductInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductInterface $productRepository
    )
    {
    }

    public function __invoke(Query $query): ?string
    {
        if (!$product = $this->productRepository->findOneBy(['nmId' => $query->nmId])) {
            return null;
        }

        return $product->getProductId();
    }
}
