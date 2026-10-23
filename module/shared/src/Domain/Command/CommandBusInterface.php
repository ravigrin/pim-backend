<?php

declare(strict_types=1);

namespace Shared\Domain\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): mixed;
}
