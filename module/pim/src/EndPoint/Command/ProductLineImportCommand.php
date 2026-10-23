<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Entity\ProductLine;
use Pim\Domain\Repository\Pim\ProductLineInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:product-line:import',
    description: '',
)] final class ProductLineImportCommand extends Command
{
    public function __construct(
        private ProductLineInterface $productLineRespotory,
        private EntityStoreService   $entityStoreService,
        string                       $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $productLines = [
            ['a1e6d36b-0e87-11ee-890a-ac1f6b72b9b1', 'DEMO BRAND A', '002', '018c8a4c-e4d4-709f-b12e-c52bbf88b97e'],
            ['a1e6d36b-0e87-11ee-890a-ac1f6b72b9b1', 'DEMO BRAND B', '003', '018c8a4c-ff7e-7391-90f5-87fd144f8a2e'],
            ['a1e6d36b-0e87-11ee-890a-ac1f6b72b9b1', 'Demo Brand L', '004', '018c8a4d-c70c-73fb-88c1-f44ac2fb31ef'],
            ['a1e6d36b-0e87-11ee-890a-ac1f6b72b9b1', 'Demo Brand K', '005', '018c8a4d-a69f-719c-b8b0-8ebe666ab2b9'],
            ['c39a86a5-27a0-11ee-8c1e-81e0f3caa235', 'Demo Brand D', '001', '018c8a4d-874d-7088-ab13-27defdcb6049'],
            ['c39a86a5-27a0-11ee-8c1e-81e0f3caa235', 'Demo Brand J', '002', '018c8a4d-672d-73e1-8d18-5d0249ad8db4'],
            ['cf1e6d26-0e87-11ee-890a-ac1f6b72b9b1', 'Demo Brand E', '005', '018c8a4d-3617-704f-b253-8c177e401625'],
        ];

        foreach ($productLines as $data) {

            $productLine = $this->productLineRespotory->findOneByCriteria(['productLineId' => $data[3]]);

            if ($productLine instanceof ProductLine) {
                continue;
            }

            $pl = new ProductLine(
                productLineId: $data[3],
                brandId: $data[0],
                name: $data[1],
                code: $data[2]
            );

            $this->entityStoreService->commit($pl);
        }
        return Command::SUCCESS;
    }
}
