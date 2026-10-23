<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Repository\Pim\AttributeGroupInterface;
use Pim\Domain\Repository\Pim\AttributeInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:attribute-group:set',
    description: 'Установить таб атрибутам',
)] final class AttributeGroupSetCommand extends Command
{
    public function __construct(
        private EntityStoreService      $entityStoreService,
        private AttributeInterface      $attributeRepository,
        private AttributeGroupInterface $attributeGroupRepository,
        string                          $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $groups = [
            'name' => 'common',
            'shortName' => 'common',
            'vendorCode' => 'common',
            'barcode' => 'common',
            'contractManagement' => 'common',
            'status' => 'common',
            'contentLink' => 'common',
            'description' => 'common',
            'usage' => 'common',
            'structure' => 'common',
            'structureRu' => 'common',
            'packaging' => 'common',
            'volume' => 'common',
            'expirationDate' => 'common',
            'costPrice' => 'common',
            'RecommendRetailPrice' => 'common',
            'packageLength' => 'common',
            'packageWidth' => 'common',
            'packageHeight' => 'common',
            'packageWeight' => 'common',
            'weight' => 'common',
            'dateStartSaleOzon' => 'common',
            'dateStartSaleWB' => 'common',
            'dateSigningSpecification' => 'common',
            'dateReceiptWarehouse' => 'common',
            'datePolygraphyLayout' => 'common',
            'datePolygraphyProofreading' => 'common',
            'datePolygraphyTransferred' => 'common',
            'dateCardsLayout' => 'common',
            'dateCardProofreading' => 'common',
            'dateCardsTransferred' => 'common',
            'packageBundle' => 'common'
        ];

        $attributes = $this->attributeRepository->findByCriteria([]);

        foreach ($attributes as $attribute) {
            $alias = $groups[$attribute->getAlias()];
            $attributeGroup = $this->attributeGroupRepository->findOneByCriteria(["alias" => $alias]);
            if (!is_null($attributeGroup)) {
                $attribute->setGroupId($attributeGroup->getAttributeGroupId());
                $this->entityStoreService->commit($attribute);
            }
        }
        return Command::SUCCESS;
    }

}
