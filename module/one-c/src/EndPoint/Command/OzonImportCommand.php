<?php

declare(strict_types=1);

namespace OneC\EndPoint\Command;

use Shared\Domain\Command\CommandBusInterface;
use OneC\Application\Command\SetBarcode\Command as SetBarcodeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: '1c:barcode:push',
    description: 'Команда для проверки отправки barcode',
)] final class OzonImportCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        string                               $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // vendorCode: 02cst0020045
        $this->commandBus->dispatch(new SetBarcodeCommand(
            nomenclatureId: '002e4873-9f0c-11ee-8c28-ca8db83f06fc',
            barcode: '000000000'
        ));

        return Command::SUCCESS;
    }
}
