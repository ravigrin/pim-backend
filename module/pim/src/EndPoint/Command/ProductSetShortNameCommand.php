<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Repository\Pim\AttributeInterface;
use Pim\Domain\Repository\Pim\ProductInterface;
use Pim\Domain\Repository\Pim\UserInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:product:set-short-name',
    description: 'Загрузка коротких имен товаров',
)] final class ProductSetShortNameCommand extends Command
{
    public function __construct(
        private readonly ProductInterface   $productRepository,
        private readonly AttributeInterface $attributeRepository,
        private readonly UserInterface      $userRepository,
        private EntityStoreService          $entityStoreService,
        string                              $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->products();

        return Command::SUCCESS;
    }

    private function products(): void
    {
        $user = $this->userRepository->findByUsername('system');
        if (is_null($user)) {
            throw new \Exception("User system not found");
        }

        $shortNameAttribute = $this->attributeRepository->findOneByCriteria(['alias' => 'shortName']);
        if (is_null($shortNameAttribute)) {
            throw new \Exception("Attribute shortName not found");
        }

        $shortNames = file(__DIR__ . '/file/short-names.csv', FILE_SKIP_EMPTY_LINES);

        foreach ($shortNames as $shortNameData) {
            $data = str_getcsv($shortNameData);
            $vendorCode = trim($data[0]);
            $shortName = trim($data[1]);

            $product = $this->productRepository->findOneByCriteria(["vendorCode" => $vendorCode]);
            if (is_null($product)) {
                echo sprintf("Not found by %s with name %s", $vendorCode, $shortName) . PHP_EOL;
                continue;
            }

            $product->setAttribute($shortNameAttribute, $user, [$shortName]);
            $this->entityStoreService->commit($product);
        }

    }
}
