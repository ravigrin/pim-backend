<?php

namespace Files\Infrastructure\Internal;

use Files\Domain\Repository\PimInterface;
use Pim\Application\Command\ProductAddImage\Command as ProductAddImageCommand;
use Shared\Domain\Command\CommandBusInterface;

class PimRepository implements PimInterface
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {
    }

    public function saveImages(string $productId, array $imagesId): void
    {
        $this->commandBus->dispatch(
            new ProductAddImageCommand(
                productId: $productId,
                imagesId: $imagesId
            )
        );
    }
}
