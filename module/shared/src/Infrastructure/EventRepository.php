<?php

declare(strict_types=1);

namespace Shared\Infrastructure;

use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\Event\EventBusInterface;
use Shared\Domain\Repository\EventInterface;

final readonly class EventRepository implements EventInterface
{
    public function __construct(private EventBusInterface $eventBus)
    {
    }

    public function store(AggregateRoot $aggregate): void
    {
        $this->eventBus->dispatch(...$aggregate->popEvents());
    }
}
