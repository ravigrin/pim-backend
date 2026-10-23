<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Catalog;
use Wildberries\Domain\Event\CatalogCreated;

readonly class CreateCatalogListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    ) {
    }

    public function __invoke(CatalogCreated $event): void
    {
        $catalog = new Catalog(
            catalogId: $event->catalogId,
            objectId: $event->objectId,
            name: $event->name,
            isVisible: $event->isVisible,
            level: $event->level,
            parentId: $event->parentId
        );
        $this->persistenceRepository->save($catalog);
    }

}
