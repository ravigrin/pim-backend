<?php

declare(strict_types=1);

namespace Ozon\EndPoint\Command;

use Ozon\Domain\Entity\AttributeGroup;
use Shared\Domain\Exception\ValueObjectException;
use Shared\Domain\Service\EntityStoreService;
use Shared\Domain\ValueObject\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ramsey\Uuid\Uuid as UuidBuild;

#[AsCommand(
    name: 'ozon:attribute-group:import',
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

    /**
     * @throws ValueObjectException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $groups = [
            "common" => "Общая",
        ];

        $i = 1;
        foreach ($groups as $alias => $group) {
            $this->entityStoreService->commit(
                new AttributeGroup(
                    attributeGroupUuid: Uuid::build(),
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
