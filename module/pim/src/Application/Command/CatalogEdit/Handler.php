<?php

declare(strict_types=1);

namespace Pim\Application\Command\CatalogEdit;

use Pim\Domain\Exception\EntityObjectException;
use Shared\Domain\Command\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct()
    {
    }

    /**
     * @throws EntityObjectException
     */
    public function __invoke(Command $command): void
    {
    }
}
