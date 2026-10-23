<?php

namespace Pim\Infrastructure\Internal;

use Shared\Domain\Command\CommandBusInterface;
use Wildberries\Application\Command\Product\Export\Command as WildberriesProductExportCommand;
use Wildberries\Application\Query\Product\GetStatus\Query as GetStatusQuery;
use Pim\Domain\Repository\Internal\WbModuleInterface;
use Shared\Domain\Query\QueryBusInterface;

final readonly class WbModuleRepository implements WbModuleInterface
{
    public function __construct(
        private QueryBusInterface   $queryBus,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function getProductStatus(string $productId): ?int
    {
        /** @var int|null $response */
        $response = $this->queryBus->dispatch(
            new GetStatusQuery($productId)
        );
        return $response;
    }

    public function pushProduct(string $productId): void
    {
        $this->commandBus->dispatch(new WildberriesProductExportCommand(
            productId: $productId
        ));
    }
}
