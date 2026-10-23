<?php

namespace Wildberries\EndPoint\Command;

use Shared\Domain\Command\CommandBusInterface;
use Wildberries\Application\Command\Attribute\Import\Command as AttributeImportCommand;
use Wildberries\Application\Command\Catalog\Import\Command as CatalogImportCommand;
use Wildberries\Application\Command\Price\Import\Command as PriceImportCommand;
use Wildberries\Application\Command\Product\Import\Command as ProductImportCommand;
use Wildberries\Application\Command\Suggest\Import\Command as SuggestImportCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'wb:import',
    description: 'Загрузка каталогов, атрибутов и товаров с Wildberries',
)] final class WbImportCommand extends Command
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
            'target',
            InputArgument::REQUIRED,
            'Target import (catalog, attribute, product, suggest, price)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $target = $input->getArgument('target');

        match ($target) {
            'catalog' => $this->commandBus->dispatch(new CatalogImportCommand()),
            'attribute' => $this->commandBus->dispatch(new AttributeImportCommand()),
            'product' => $this->commandBus->dispatch(new ProductImportCommand()),
            'suggest' => $this->commandBus->dispatch(new SuggestImportCommand()),
            'price' => $this->commandBus->dispatch(new PriceImportCommand()),
            default => print_r('FAIL: target invalid' . PHP_EOL, true)
        };

        return Command::SUCCESS;
    }
}
