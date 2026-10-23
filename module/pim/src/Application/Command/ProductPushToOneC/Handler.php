<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductPushToOneC;

use Pim\Domain\Service\PushProductToOneC;
use Shared\Domain\Command\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private PushProductToOneC $pushProductToOneC,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $this->pushProductToOneC->handler($command->productId);
    }
}
