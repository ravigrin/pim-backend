<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Suggest;
use Wildberries\Domain\Event\SuggestCreated;

readonly class CreateSuggestListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    ) {
    }

    public function __invoke(SuggestCreated $event): void
    {
        $catalog = new Suggest(
            suggestId: $event->suggestId,
            catalogId: $event->catalogId,
            objectId: $event->objectId,
            value: $event->value,
            info: $event->info
        );

        $this->persistenceRepository->save($catalog);
    }

}
