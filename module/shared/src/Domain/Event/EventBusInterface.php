<?php

declare(strict_types=1);

namespace Shared\Domain\Event;

interface EventBusInterface
{
    public function dispatch(EventInterface ...$events): void;
}
