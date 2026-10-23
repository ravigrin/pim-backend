<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Application\Command\ProductDwhImport\Command as ProductDwhImport;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Command\CommandInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:product:import',
    description: 'Загрузка товаров из dwh',
)] final class ProductImportCommand extends Command implements CommandInterface
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
        $this->commandBus->dispatch(command: new ProductDwhImport());
        return self::SUCCESS;
    }
}
