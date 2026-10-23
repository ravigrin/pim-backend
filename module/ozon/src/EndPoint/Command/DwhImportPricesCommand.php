<?php

declare(strict_types=1);

namespace Ozon\EndPoint\Command;

use Ozon\Domain\Repository\AttributeInterface;
use Ozon\Domain\Repository\Dwh\OzonPriceInterface;
use Ozon\Domain\Repository\ProductInterface;
use Ozon\Domain\Service\AddProductAttribute;
use Pim\Domain\Repository\Pim\UserInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ozon:dwn:prices',
    description: 'Загрузить себестоимость товаров ozon',
)] final class DwhImportPricesCommand extends Command
{
    public function __construct(
        private readonly ProductInterface    $productRepository,
        private readonly AttributeInterface  $attributeRepository,
        private readonly EntityStoreService  $entityStoreService,
        private readonly OzonPriceInterface  $priceRepository,
        private readonly UserInterface       $userRepository,
        private readonly AddProductAttribute $addProductAttribute,
        string                               $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->userRepository->findByUsername("system");
        if (is_null($user)) {
            throw new \Exception("User system not found");
        }

        $costPriceAttribute = $this->attributeRepository->findOneByCriteria(["alias" => "costPrice"]);
        if (is_null($costPriceAttribute)) {
            throw new \Exception("Attribute costPrice not found");
        }

        $salePercentAttribute = $this->attributeRepository->findOneByCriteria(["alias" => "salePercent"]);
        if (is_null($salePercentAttribute)) {
            throw new \Exception("Attribute salePercent not found");
        }

        $productionPriceAttribute = $this->attributeRepository->findOneByCriteria(["alias" => "productionPrice"]);
        if (is_null($productionPriceAttribute)) {
            throw new \Exception("Attribute productionPrice not found");
        }

        $productionPriceFlagAttribute = $this->attributeRepository->findOneByCriteria(["alias" => "productionPriceFlag"]);
        if (is_null($productionPriceFlagAttribute)) {
            throw new \Exception("Attribute productionPriceFlag not found");
        }

        $products = $this->productRepository->findProductsWithPrice();

        foreach ($products as $product) {

            if (!$oldPrice = (float)$product['oldPrice']) {
                continue;
            }

            $vendorCode = $product['vendorCode'];
            $price = (float)$product['price'];

            $priceJson = $this->priceRepository->findCostPriceBy($vendorCode, $oldPrice);

            $costPrice = (float)$priceJson['Себестоимость'];
            $productionPrice = (float)$priceJson['СебестоимостьПроизводства'];
            $productionPriceFlag = (bool)$priceJson['СебестоимостьПроизводстваЗадана'];

            $salePercent = 100 - round(100 / ($oldPrice / $price));

            print_r([
                'productId' => $product["productId"],
                'vendorCode' => $vendorCode,
                'oldPrice' => $oldPrice,
                'price' => $price,
                'costPrice' => $costPrice,
                'salePercent' => $salePercent,
                'productionPrice' => $productionPrice,
                'productionPriceFlag' => $productionPriceFlag
            ]);

            $product = $this->productRepository->findOneByCriteria(["productUuid" => $product["productId"]]);
            if (is_null($product)) {
                continue;
            }

            $this->addProductAttribute->handler(
                product: $product,
                attribute: $costPriceAttribute,
                user: $user,
                value: [$costPrice],
                prepareValue: []
            );
            $this->addProductAttribute->handler(
                product: $product,
                attribute: $salePercentAttribute,
                user: $user,
                value: [$salePercent],
                prepareValue: []
            );
            $this->addProductAttribute->handler(
                product: $product,
                attribute: $productionPriceAttribute,
                user: $user,
                value: [$productionPrice],
                prepareValue: []
            );
            $this->addProductAttribute->handler(
                product: $product,
                attribute: $productionPriceFlagAttribute,
                user: $user,
                value: [$productionPriceFlag],
                prepareValue: []
            );

            $this->entityStoreService->commit($product);
        }

        return Command::SUCCESS;
    }

}
