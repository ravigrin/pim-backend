<?php

declare(strict_types=1);

namespace OneC\Application\Command\SetBarcode;

use OneC\Domain\Service\SetBarcodeService;
use Shared\Domain\Command\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private SetBarcodeService $setBarcodeService,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $this->setBarcodeService->handel(
            nomenclatureId: $command->nomenclatureId,
            barcode: $command->barcode
        );
    }
}
