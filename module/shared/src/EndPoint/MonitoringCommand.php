<?php

declare(strict_types=1);

namespace Shared\EndPoint;

use Shared\Application\Command\Monitoring\Command as DwhMonitoringCommand;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'shared:monitoring:check',
    description: 'Проверить данные в dwh по sql Васи',
)] final class MonitoringCommand extends Command
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
        $this->commandBus->dispatch(new DwhMonitoringCommand());

        return Command::SUCCESS;
    }
}
