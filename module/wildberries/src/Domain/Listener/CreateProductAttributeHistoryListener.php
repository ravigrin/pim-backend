<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\ProductAttributeHistory;
use Wildberries\Domain\Event\ProductAttributeHistoryCreated;

readonly class CreateProductAttributeHistoryListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    ) {
    }

    public function __invoke(ProductAttributeHistoryCreated $event): void
    {
        $catalog = new ProductAttributeHistory(
            productAttributeHistoryId: $event->productAttributeHistoryId,
            userId: $event->userId,
            productAttributeId: $event->productAttributeId,
            newValue: $event->newValue,
            oldValue: $event->oldValue
        );

        $this->persistenceRepository->save($catalog);
    }

}
