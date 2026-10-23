<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Entity\Brand;
use Pim\Domain\Repository\Pim\BrandInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:brand:import',
    description: 'Загрузка брендов',
)] final class BrandImportCommand extends Command
{
    public function __construct(
        private EntityStoreService $entityStoreService,
        private BrandInterface     $brandRepository,
        string                     $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $brands = file(__DIR__ . '/file/brands.csv');
        if (empty($brands)) {
            return self::FAILURE;
        }

        foreach ($brands as $brand) {
            /** @var string[] $data */
            $data = str_getcsv($brand, ';');
            if (empty($data[1])) {
                continue;
            }
            $guid = trim($data[0]);
            $name = trim($data[1]);
            $code = trim($data[2]);

            $brand = $this->brandRepository->findOneByCriteria(["brandId" => $guid]);

            if (is_null($brand)) {
                $brand = new Brand(
                    brandId: $guid,
                    name: $name,
                    code: $code
                );
                $this->entityStoreService->commit($brand);
            }

        }

        return Command::SUCCESS;
    }

}
