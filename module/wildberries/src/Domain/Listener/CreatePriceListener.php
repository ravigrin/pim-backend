<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Price;
use Wildberries\Domain\Event\PriceCreated;

readonly class CreatePriceListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    )
    {
    }

    public function __invoke(PriceCreated $event): void
    {
        $catalog = new Price(
            productId: $event->productId,
            price: $event->price,
            discount: $event->discount,
            finalPrice: $event->finalPrice,
            isStockAvailable: $event->isStockAvailable,
            netCost: $event->netCost,
            productionCost: $event->productionCost,
            productionCostFlag: $event->productionCostFlag
        );
        $this->persistenceRepository->save($catalog);

    }

}
