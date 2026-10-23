<?php

namespace Shared\Domain\Service;

use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\Repository\EventInterface;
use Shared\Domain\Repository\PersistenceInterface;

readonly class EntityStoreService
{
    public function __construct(
        private PersistenceInterface $repository,
        private EventInterface       $event
    ) {
    }

    public function commit(AggregateRoot $entity): void
    {
        $this->event->store($entity);
        $this->repository->save($entity);
    }
}
