<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Entity\AttributeGroup;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:attribute-group:import',
    description: 'Загрузка табов атрибутов',
)] final class AttributeGroupImportCommand extends Command
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
        $groups = [
            "common" => "Общая",
            "packaging" => "Упаковка",
            "net-weight" => "Нетто",
        ];

        $i = 1;
        foreach ($groups as $alias => $group) {
            $this->entityStoreService->commit(
                new AttributeGroup(
                    attributeGroupId: Uuid::uuid7()->toString(),
                    name: $group,
                    alias: $alias,
                    customOrder: $i
                )
            );
            $i++;
        }
        return Command::SUCCESS;
    }

}
