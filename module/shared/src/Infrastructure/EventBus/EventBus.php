<?php

declare(strict_types=1);

namespace Shared\Infrastructure\EventBus;

use Shared\Domain\Event\EventInterface;
use Shared\Domain\Event\EventBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class EventBus implements EventBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function dispatch(EventInterface ...$events): void
    {
        foreach ($events as $currentEvent) {
            $this->messageBus->dispatch(
                (new Envelope($currentEvent))->with(new DispatchAfterCurrentBusStamp())
            );
        }
    }
}
