<?php

declare(strict_types=1);

namespace Pim\Application\Query\Product\GetUuidByVendorCode;

use Pim\Domain\Repository\Pim\ProductInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductInterface $productRepository,
    ) {
    }

    public function __invoke(Query $query): ?string
    {
        $product = $this->productRepository->findOneByCriteria([
            "vendorCode" => $query->vendorCode
        ]);

        if (is_null($product)) {
            return null;
        }

        return $product->getProductId();
    }
}
