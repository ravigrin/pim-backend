<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\AttributeMap;
use Wildberries\Domain\Event\AttributeMapCreated;

readonly class CreateAttributeMapListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    ) {
    }

    public function __invoke(AttributeMapCreated $event): void
    {
        $catalog = new AttributeMap(
            attributeMapId: $event->attributeMapId,
            wbAttributeId: $event->wbAttributeId,
            pimAttributeId: $event->pimAttributeId,
            wbName: $event->wbName,
            pimAlias: $event->pimAlias,
            wbMeasure: $event->wbMeasure,
            pimMeasure: $event->pimMeasure
        );

        $this->persistenceRepository->save($catalog);
    }

}
