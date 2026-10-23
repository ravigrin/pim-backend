<?php

namespace Pim\Infrastructure\Internal;

use OneC\Application\Command\CreateNomenclature\Command as CreateNomenclatureCommand;
use Pim\Domain\Repository\Internal\OneCModuleInterface;
use Shared\Domain\Command\CommandBusInterface;

final readonly class OneCModuleRepository implements OneCModuleInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @param string[] $products
     */
    public function pushProduct(
        string  $nomenclatureId,
        string  $brandId,
        string  $nomenclatureName,
        string  $brandName,
        string  $vendorCode,
        bool    $isKit,
        array   $products,
        ?string $barcode,
        ?string $productLineName,
    ): void {
        $this->commandBus->dispatch(
            new CreateNomenclatureCommand(
                $nomenclatureId,
                $brandId,
                $nomenclatureName,
                $brandName,
                $vendorCode,
                $isKit,
                $products,
                $barcode,
                $productLineName,
            )
        );
    }
}
