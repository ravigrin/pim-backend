<?php

declare(strict_types=1);

namespace Ozon\EndPoint\Command;

use Ozon\Application\Command\AttributeImport\Command as ImportAttributesCommand;
use Ozon\Application\Command\CatalogImport\Command as ImportCatalogsCommand;
use Ozon\Application\Command\DictionaryImport\Command as ImportDictionaryCommand;
use Ozon\Application\Command\ProductImport\Command as ImportProductCommand;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ozon:import',
    description: 'Обновление ozon',
)] final class OzonImportCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument(
            'target',
            InputArgument::REQUIRED,
            'Target import (catalog, attribute, dictionary)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $target = $input->getArgument('target');

        match ($target) {
            'catalog' => $this->commandBus->dispatch(new ImportCatalogsCommand()),
            'attribute' => $this->commandBus->dispatch(new ImportAttributesCommand()),
            'dictionary' => $this->commandBus->dispatch(new ImportDictionaryCommand()),
            'product' => $this->commandBus->dispatch(new ImportProductCommand()),
            default => 'target not exits'
        };

        return Command::SUCCESS;
    }
}
