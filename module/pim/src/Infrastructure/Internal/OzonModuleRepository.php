<?php

namespace Pim\Infrastructure\Internal;

use Ozon\Application\Command\ProductExport\Command as OzonProductExportCommand;
use Ozon\Application\Query\Product\GetStatus\Query as GetStatusQuery;
use Pim\Domain\Repository\Internal\OzonModuleInterface;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;

final readonly class OzonModuleRepository implements OzonModuleInterface
{
    public function __construct(
        private QueryBusInterface   $queryBus,
        private CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @param string $productId
     * @return int|null
     */
    public function getOzonStatus(string $productId): ?int
    {
        /** @var int|null $response */
        $response = $this->queryBus->dispatch(
            new GetStatusQuery($productId)
        );
        return $response;
    }

    public function pushProduct(string $productId): void
    {
        $this->commandBus->dispatch(new OzonProductExportCommand(
            productId: $productId
        ));
    }
}
