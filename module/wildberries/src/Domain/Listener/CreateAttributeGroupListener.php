<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\AttributeGroup;
use Wildberries\Domain\Event\AttributeGroupCreated;

readonly class CreateAttributeGroupListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    ) {
    }

    public function __invoke(AttributeGroupCreated $event): void
    {
        $catalog = new AttributeGroup(
            attributeGroupId: $event->attributeGroupId,
            name: $event->name,
            alias: $event->alias,
            type: $event->type,
            orderGroup: $event->orderGroup,
            tabId: $event->tabId
        );

        $this->persistenceRepository->save($catalog);
    }

}
