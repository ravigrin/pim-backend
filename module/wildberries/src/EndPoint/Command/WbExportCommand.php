<?php

namespace Wildberries\EndPoint\Command;

use Shared\Domain\Command\CommandBusInterface;
use Wildberries\Application\Command\Product\Export\Command as ProductExportCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'wb:export:product',
    description: 'Выгрузка товаров на Wildberries',
)] final class WbExportCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        string                               $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument(
            'productId',
            InputArgument::OPTIONAL,
            'Product Id'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $productId = $input->getArgument('productId') ?? null;

        if ($productId) {
            $this->commandBus->dispatch(new ProductExportCommand(
                productId: $productId
            ));
            return Command::SUCCESS;
        }

        $this->commandBus->dispatch(new ProductExportCommand());

        return Command::SUCCESS;
    }
}
