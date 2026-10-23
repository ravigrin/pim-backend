<?php

declare(strict_types=1);

namespace Ozon\EndPoint\Command;

use Ozon\Application\Command\ProductExport\Command as ProductExportCommand;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ozon:export:product',
    description: 'ozon',
)] final class OzonExportCommand extends Command
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
        $this->commandBus->dispatch(new ProductExportCommand(
            productId: 'A7350E19-0E80-11EE-890A-AC1F6B72B9B1'
        ));

        return Command::SUCCESS;
    }
}
