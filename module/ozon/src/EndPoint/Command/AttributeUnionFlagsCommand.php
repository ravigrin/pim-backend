<?php

namespace Ozon\EndPoint\Command;

use Ozon\Application\Command\SetUnionFlags\Command as SetUnionFlagsCommand;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ozon:attribute:set-union-flags',
    description: 'Проставление доп параметров озон по объединению',
)] final class AttributeUnionFlagsCommand extends Command
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
        $this->commandBus->dispatch(new SetUnionFlagsCommand());

        return Command::SUCCESS;
    }
}
