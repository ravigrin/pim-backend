<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Repository\Pim\AttributeInterface;
use Pim\Domain\Repository\Pim\AttributeTabInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:attribute-tab:set',
    description: 'Установить таб атрибутам',
)] final class AttributeTabSetCommand extends Command
{
    public function __construct(
        private EntityStoreService    $entityStoreService,
        private AttributeInterface    $attributeRepository,
        private AttributeTabInterface $attributeTabRepository,
        string                        $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tabs = [
            'name' => 'common-information',
            'shortName' => 'common-information',
            'vendorCode' => 'common-information',
            'contractManagement' => 'common-information',
            'barcode' => 'common-information',
            'status' => 'common-information',
            'contentLink' => 'common-information',
            'description' => 'common-information',
            'usage' => 'common-information',
            'structure' => 'common-information',
            'structureRu' => 'common-information',
            'packaging' => 'dimensions',
            'volume' => 'dimensions',
            'expirationDate' => 'common-information',
            'costPrice' => 'common-information',
            'RecommendRetailPrice' => 'common-information',
            'packageBundle' => 'common-information',
            'packageLength' => 'dimensions',
            'packageWidth' => 'dimensions',
            'packageHeight' => 'dimensions',
            'packageWeight' => 'dimensions',
            'weight' => 'dimensions',
            'dateStartSaleOzon' => 'product-statuses',
            'dateStartSaleWB' => 'product-statuses',
            'dateSigningSpecification' => 'product-statuses',
            'dateReceiptWarehouse' => 'product-statuses',
            'datePolygraphyLayout' => 'product-statuses',
            'datePolygraphyProofreading' => 'product-statuses',
            'datePolygraphyTransferred' => 'product-statuses',
            'dateCardsLayout' => 'product-statuses',
            'dateCardProofreading' => 'product-statuses',
            'dateCardsTransferred' => 'product-statuses',
        ];

        $attributes = $this->attributeRepository->findByCriteria([]);

        foreach ($attributes as $attribute) {
            $tab = $tabs[$attribute->getAlias()];
            $attributeTab = $this->attributeTabRepository->findOneByCriteria(["alias" => $tab]);
            if (!is_null($attributeTab)) {
                $attribute->setTabId($attributeTab->getAttributeTabId());
                $this->entityStoreService->commit($attribute);
            }
        }
        return Command::SUCCESS;
    }

}
