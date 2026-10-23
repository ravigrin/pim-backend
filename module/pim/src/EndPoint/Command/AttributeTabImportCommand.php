<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Entity\AttributeTab;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:attribute-tab:import',
    description: 'Загрузка табов атрибутов',
)] final class AttributeTabImportCommand extends Command
{
    public function __construct(
        private EntityStoreService $entityStoreService,
        string                     $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tabs = [
            "common-information" => "Общая информация",
            "dimensions" => "Габариты и вес",
            "product-statuses" => "Статусы товара",
        ];

        $i = 1;
        foreach ($tabs as $alias => $tab) {
            $this->entityStoreService->commit(
                new AttributeTab(
                    attributeTabId: Uuid::uuid7()->toString(),
                    name: $tab,
                    alias: $alias,
                    customOrder: $i
                )
            );
            $i++;
        }
        return Command::SUCCESS;
    }

}
