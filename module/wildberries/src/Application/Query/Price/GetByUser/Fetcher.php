<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Price\GetByUser;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Application\Query\Price\PriceDto;
use Wildberries\Domain\Repository\PriceInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private PriceInterface $priceRepository
    )
    {
    }

    /**
     * @return PriceDto[]
     */
    public function __invoke(Query $query): array
    {
        return array_map(
            fn(array $priceList): PriceDto => new PriceDto(
                productId: $priceList['product_id'],
                image: $priceList['little'],
                brand: $priceList['brand'],
                object: $priceList['name'],
                articleWb: $priceList['nm_id'],
                articlePim: $priceList['vendor_code'],
                price: (int)$priceList['price'],
                discount: (int)$priceList['discount'],
                totalPrice: (int)$priceList['final_price'],
                costPrice: (float)$priceList['net_cost'],
                productionPrice: (float)$priceList['production_cost'],
                productionPriceFlag: (bool)$priceList['production_cost_flag']
            ),
            $this->priceRepository->getList()
        );
    }
}
