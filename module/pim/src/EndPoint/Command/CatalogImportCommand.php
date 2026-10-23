<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Entity\Catalog;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:catalog:import',
    description: 'Загрузка брендов',
)] final class CatalogImportCommand extends Command
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
        $catalogs = file(__DIR__ . '/file/catalogs.csv');

        if (is_array($catalogs)) {
            foreach ($catalogs as $catalog) {
                /** @var string[] $data */
                $data = str_getcsv($catalog);
                if (empty($data[1])) {
                    continue;
                }
                $level1 = trim($data[0]);
                $level2 = trim($data[1]);
                $level3 = trim($data[2]);
                $level4 = trim($data[3]);

                $brand = new Catalog(
                    catalogId: Uuid::uuid7()->toString(),
                    name: $level4,
                    treePath: sprintf("%s / %s / %s / %s", $level1, $level2, $level3, $level4),
                    level: 4
                );
                $this->entityStoreService->commit($brand);
            }
        }


        return Command::SUCCESS;
    }

}
