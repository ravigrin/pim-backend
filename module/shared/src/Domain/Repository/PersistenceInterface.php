<?php

namespace Shared\Domain\Repository;

use Shared\Domain\Aggregate\AggregateRoot;

interface PersistenceInterface
{
    public function persist(AggregateRoot $entity): void;

    public function flush(): void;

    public function save(AggregateRoot $entity): void;

}
