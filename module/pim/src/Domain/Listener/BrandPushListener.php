<?php

namespace Pim\Domain\Listener;

use Pim\Domain\Event\BrandPushed;
use OneC\Application\Command\CreateBrand\Command as CreateBrandCommand;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Event\EventHandlerInterface;

readonly class BrandPushListener implements EventHandlerInterface
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {
    }

    public function __invoke(BrandPushed $event): void
    {
        $this->commandBus->dispatch(new CreateBrandCommand(
            brandId: $event->brandId,
            brandName: $event->brandName
        ));
    }
}
