<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:product:build-file',
    description: 'СОздать csv файл товаров',
)] final class ProductBuildFileCommand extends Command
{
    public function __construct(
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $brandsUuid = [
            'Demo Brand A' => 'a1e6d36b-0e87-11ee-890a-ac1f6b72b9b1',
            'Demo Brand C' => '3ad97837-0e7f-11ee-890a-ac1f6b72b9b1',
            'Demo Brand D' => '438648c6-2d21-11ee-8c1e-81e0f3caa235',
            'Demo Brand F' => 'cf1e6d26-0e87-11ee-890a-ac1f6b72b9b1',
            'Demo Brand G' => '0347f862-34ff-11ee-8c1e-81e0f3caa235',
            'Demo Brand H' => '2707536e-34ff-11ee-8c1e-81e0f3caa235',
            'Demo Brand I' => '7fde39b4-52d8-11ee-8c20-bc6a09a24683',
            'Demo Brand J' => 'c39a86a5-27a0-11ee-8c1e-81e0f3caa235',
        ];

        $productLinesUuid = [
            'DEMO BRAND A' => '018c8a4c-e4d4-709f-b12e-c52bbf88b97e',
            'DEMO BRAND B' => '018c8a4c-ff7e-7391-90f5-87fd144f8a2e',
            'Demo Brand L' => '018c8a4d-c70c-73fb-88c1-f44ac2fb31ef',
            'Demo Brand K' => '018c8a4d-a69f-719c-b8b0-8ebe666ab2b9',
            'Demo Brand D' => '018c8a4d-874d-7088-ab13-27defdcb6049',
            'Demo Brand J' => '018c8a4d-672d-73e1-8d18-5d0249ad8db4',
            'Demo Brand E' => '018c8a4d-3617-704f-b253-8c177e401625',
        ];

        $units = [
            '01' => '018c5782-e31a-73e3-abbc-19d888763137',
            '02' => '018c5782-e330-73e4-9d3a-73c94925824c'
        ];

        $brands = [
            'DBA' => ['Demo Brand A', [
                '002' => 'DEMO BRAND A',
                '003' => 'DEMO BRAND B',
                '004' => 'Demo Brand L',
                '005' => 'Demo Brand K',
            ]],
            'DBC' => ['Demo Brand C'],
            'DCA' => ['Demo Brand C'],
            'DDD' => ['Demo Brand D'],
            'DFF' => ['Demo Brand F', [
                '004' => 'Demo Brand E',
                '005' => 'Demo Brand E',
                '006' => 'Demo Brand E',
            ]],
            'DGG' => ['Demo Brand G'],
            'DHH' => ['Demo Brand H'],
            'DII' => ['Demo Brand I'],
            'DJB' => ['Demo Brand I'],
            'DJJ' => ['Demo Brand J', [
                '001' => 'Demo Brand D',
                '002' => 'Demo Brand J',
            ]],
        ];

        $products = file(__DIR__ . '/file/tmp-products.csv', FILE_SKIP_EMPTY_LINES);
        if (empty($products)) {
            $output->writeln('product file empty');
            return self::FAILURE;
        }

        $buildProducts = [];

        foreach ($products as $product) {
            /** @var string[] $data */
            $data = str_getcsv($product);
            if (empty($data[1])) {
                continue;
            }

            list($productUuid, $productName, $vendorCode, $brandUuid, $productType) = $data;

            if (empty($vendorCode)) {
                $output->writeln("empty vendorCode");
                continue;
            }

            if (strlen($vendorCode) !== 12) {
                $buildProducts[] = [
                    $productUuid, $productName, $vendorCode, $brandUuid, $productType,
                ];
                continue;
            }

            $unitCode = substr($vendorCode, offset: 0, length: 2);
            $brandCode = substr($vendorCode, offset: 2, length: 3);
            $productLineCode = substr($vendorCode, offset: 5, length: 3);

            $unitUuid = $units[$unitCode];

            $brandName = $brands[strtoupper($brandCode)][0];
            $brandUuid = $brandsUuid[$brandName];

            $productLineName = null;
            $productLineUuid = '';
            if ($productLineCode !== '000') {
                $productLineName = $brands[strtoupper($brandCode)][1][$productLineCode];
                $productLineUuid = $productLinesUuid[$productLineName];
            }

            echo sprintf(
                'u:%s %s:%s:%s %s:%s:%s',
                $unitCode,
                $brandCode,
                $brandName,
                $brandUuid,
                $productLineCode,
                $productLineName,
                $productLineUuid
            ) . PHP_EOL;

            $buildProducts[] = [
                $productUuid, $productName, $vendorCode, $brandUuid, $productType, $unitUuid, $productLineUuid,
            ];

            //            $brand = $this->brandRepository->findOneByCriteria(['brandId' => $brandUuid]);
            //
            //            $product = $this->productRepository->findByVendorCode($vendorCode);
        }

        $fp = fopen(__DIR__ . '/file/products.csv', 'w');

        foreach ($buildProducts as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        return self::SUCCESS;
    }
}
