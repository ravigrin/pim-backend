<?php

declare(strict_types=1);

namespace Shared\Domain\Repository;

use Shared\Domain\Aggregate\AggregateRoot;

interface EventInterface
{
    public function store(AggregateRoot $aggregate): void;
}
