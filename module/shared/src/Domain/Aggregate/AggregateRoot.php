<?php

declare(strict_types=1);

namespace Shared\Domain\Aggregate;

use Shared\Domain\Event\EventInterface;

abstract class AggregateRoot
{
    /**
     * @var EventInterface[]
     */
    private array $events = [];

    public function apply(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return EventInterface[]
     */
    public function popEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
