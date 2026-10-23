<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Price\GetAll;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Application\Query\Price\PriceDto;
use Wildberries\Domain\Entity\Price;
use Wildberries\Domain\Repository\PriceInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private PriceInterface $priceRepository
    ) {
    }

    /**
     * @return PriceDto[]
     * @throws \Exception
     */
    public function __invoke(Query $query): array
    {
        return array_map(
            static fn (Price $price): PriceDto => new PriceDto(
                productId: $price->getProductId(),
                price: $price->getPrice(),
                discount: $price->getDiscount(),
                totalPrice: $price->getFinalPrice(),
                costPrice: $price->getNetCost(),
                productionPrice: $price->getProductionCost(),
                productionPriceFlag: $price->getProductionCostFlag(),
            ),
            $this->priceRepository->findBy([])
        );
    }
}
