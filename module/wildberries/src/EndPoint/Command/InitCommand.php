<?php

namespace Wildberries\EndPoint\Command;

use Shared\Domain\Command\CommandBusInterface;
use Wildberries\Application\Command\Attribute\Group\Init\Command as AttributeGroupInitCommand;
use Wildberries\Application\Command\Attribute\MatchPim\Command as AttributeMatchPimCommand;
use Wildberries\Application\Command\Attribute\Module\Fill\Command as AttributeModuleFillCommand;
use Wildberries\Application\Command\Attribute\Module\Init\Command as AttributeModuleInitCommand;
use Wildberries\Application\Command\Attribute\SetDefaultValue\Command as SetDefaultValueCommand;
use Wildberries\Application\Command\Price\SetNetCost\Command as PriceSetNetCostCommand;
use Wildberries\Infrastructure\Service\AttributeGroupService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'wb:init',
    description: 'Загрузка базовых групп атрибутов и распределение загруженных атрибутов по группам',
)] final class InitCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface   $commandBus,
        private readonly AttributeGroupService $groupService,
        string                                 $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument(
            'target',
            InputArgument::REQUIRED,
            'Target import (group-seeder, group-setter, set-default-values, 
            init-module-attribute, fill-module-attribute, pim-attr-match, fill-net-cost)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $target = $input->getArgument('target');

        match ($target) {
            'group-seeder' => $this->groupService->fillGroups(),
            'group-setter' => $this->commandBus->dispatch(new AttributeGroupInitCommand()),
            'set-default-values' => $this->commandBus->dispatch(new SetDefaultValueCommand()),
            'init-module-attribute' => $this->commandBus->dispatch(new AttributeModuleInitCommand()),
            'fill-module-attribute' => $this->commandBus->dispatch(new AttributeModuleFillCommand()),
            'pim-attr-match' => $this->commandBus->dispatch(new AttributeMatchPimCommand()),
            'fill-net-cost' => $this->commandBus->dispatch(new PriceSetNetCostCommand()),
            default => print_r('FAIL: target invalid' . PHP_EOL, true)
        };

        return Command::SUCCESS;
    }


}
