<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Entity\Unit;
use Pim\Domain\Repository\Pim\UnitInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:unit:import',
    description: 'Загрузка табов атрибутов',
)] final class UnitImportCommand extends Command
{
    public function __construct(
        private UnitInterface      $unitRepository,
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
        $units = file(__DIR__ . '/file/units.csv', FILE_SKIP_EMPTY_LINES);
        if (empty($units)) {
            $output->writeln('units file empty');
            return self::FAILURE;
        }

        foreach ($units as $data) {

            $data = str_getcsv($data);

            list($unitUuid, $unitName, $unitCode) = $data;

            $unitEntity = $this->unitRepository->findOneByCriteria(['unitId' => $unitUuid]);

            if (is_null($unitEntity)) {

                $unitEntity = new Unit(
                    unitId: $unitUuid,
                    name: $unitName,
                    code: $unitCode
                );
            }

            $this->entityStoreService->commit($unitEntity);
        }
        return Command::SUCCESS;
    }

}
