<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Price\GetNetCost;

use Ozon\Domain\Repository\Dwh\OzonPriceInterface;
use Ozon\Domain\Repository\ProductInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductInterface   $productRepository,
        private OzonPriceInterface $priceRepository,
    ) {
    }

    public function __invoke(Query $query): float
    {
        $product = $this->productRepository->findOneByCriteria(["productUuid" => $query->productId]);

        if (is_null($product)) {
            throw new \Exception(sprintf("Product %s not found", $query->productId));
        }

        $price = $this->priceRepository->findCostPriceBy(
            vendorCode: $product->getOfferId(),
            sellingPrice: $query->price
        );

        return (float)$price['Себестоимость'];
    }
}
