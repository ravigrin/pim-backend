<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Event\AttributeCreated;

readonly class CreateAttributeListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    )
    {
    }

    public function __invoke(AttributeCreated $event): void
    {
        $catalog = new Attribute(
            attributeId: $event->attributeId,
            name: $event->name,
            type: $event->type,
            charcType: $event->charcType,
            maxCount: $event->maxCount,
            source: $event->source,
            measurement: $event->measurement,
            description: $event->description,
            alias: $event->alias,
            groupId: $event->groupId,
            isRequired: $event->isRequired,
            isPopular: $event->isPopular,
            isDictionary: $event->isDictionary,
            isReadOnly: $event->isReadOnly,
            isVisible: $event->isVisible
        );

        $this->persistenceRepository->save($catalog);
    }

}
