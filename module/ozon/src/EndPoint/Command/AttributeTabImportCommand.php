<?php

declare(strict_types=1);

namespace Ozon\EndPoint\Command;

use Ozon\Domain\Entity\AttributeTab;
use Ramsey\Uuid\Uuid as UuidBuild;
use Shared\Domain\Exception\ValueObjectException;
use Shared\Domain\Service\EntityStoreService;
use Shared\Domain\ValueObject\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ozon:attribute-tab:import',
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

    /**
     * @throws ValueObjectException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tabs = [
            "common-information" => "Общая информация",
            "dimensions" => "Габариты и вес",
            "specifications" => "Характеристики",
            "media-files" => "Медиафайлы",
        ];

        $i = 1;
        foreach ($tabs as $alias => $tab) {
            $this->entityStoreService->commit(
                new AttributeTab(
                    attributeTabUuid: new Uuid(UuidBuild::uuid7()->toString()),
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
