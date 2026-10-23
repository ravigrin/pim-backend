<?php

namespace Pim\Domain\Listener;

use Pim\Domain\Entity\ProductAttributeHistory;
use Pim\Domain\Event\AttributeHistoryAdded;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;

readonly class AttributeHistoryListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    ) {
    }

    public function __invoke(AttributeHistoryAdded $event): void
    {
        $history = new ProductAttributeHistory(
            productAttributeHistoryId: Uuid::uuid7()->toString(),
            productAttributeId: $event->productAttributeId,
            productId: $event->productId,
            attributeId: $event->attributeId,
            userId: $event->userId,
            newValue: $event->value,
            oldValue: $event->oldValue
        );
        $this->persistenceRepository->save($history);
    }
}
