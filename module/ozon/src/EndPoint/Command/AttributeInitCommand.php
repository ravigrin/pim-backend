<?php

namespace Ozon\EndPoint\Command;

use Ozon\Domain\Entity\Attribute;
use Ozon\Domain\Repository\AttributeGroupInterface;
use Ozon\Domain\Repository\AttributeInterface;
use Ozon\Domain\Repository\AttributeTabInterface;
use Shared\Domain\Service\EntityStoreService;
use Shared\Domain\ValueObject\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ozon:attribute:init',
    description: 'Загружает базовые атрибуты',
)] final class AttributeInitCommand extends Command
{
    public function __construct(
        private AttributeInterface      $attributeRepository,
        private AttributeTabInterface   $attributeTabRepository,
        private AttributeGroupInterface $attributeGroupRepository,
        private EntityStoreService      $entityStoreService,
        string                          $name = null
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
        $attributes = [
            "complexAttributes" => [
                "name" => "Комплекстные атрибуты",
                "description" => "",
                "pimType" => "json",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => false,
                "readOnly" => true,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "primaryImage" => [
                "name" => "Основное фото товара",
                "description" => "",
                "pimType" => "string",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => true,
                "readOnly" => true,
                "tabAlias" => "media-files",
                "groupAlias" => "common",
            ],
            "images" => [
                "name" => "Изображения",
                "description" => "",
                "pimType" => "string[]",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => true,
                "readOnly" => true,
                "tabAlias" => "media-files",
                "groupAlias" => "common",
            ],
            "images360" => [
                "name" => "Изображения 360",
                "description" => "",
                "pimType" => "string[]",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => true,
                "readOnly" => true,
                "tabAlias" => "media-files",
                "groupAlias" => "common",
            ],
            "name" => [
                "name" => "Название",
                "description" => "",
                "pimType" => "string",
                "ozonType" => "",
                "isRequired" => true,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "oldPrice" => [
                "name" => "Цена до скидки",
                "description" => "",
                "pimType" => "integer",
                "ozonType" => "",
                "isRequired" => true,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "premiumPrice" => [
                "name" => "Premium цена",
                "description" => "",
                "pimType" => "integer",
                "ozonType" => "",
                "isRequired" => true,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "price" => [
                "name" => "Цена",
                "description" => "",
                "pimType" => "integer",
                "ozonType" => "",
                "isRequired" => true,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "depth" => [
                "name" => "Длина упаковки",
                "description" => "",
                "pimType" => "integer",
                "ozonType" => "",
                "isRequired" => true,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "dimensions",
                "groupAlias" => "common",
            ],
            "width" => [
                "name" => "Ширина упаковки",
                "description" => "",
                "pimType" => "integer",
                "ozonType" => "",
                "isRequired" => true,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "dimensions",
                "groupAlias" => "common",
            ],
            "height" => [
                "name" => "Высота упаковки",
                "description" => "",
                "pimType" => "integer",
                "ozonType" => "",
                "isRequired" => true,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "dimensions",
                "groupAlias" => "common",
            ],
            "weight" => [
                "name" => "Вес в упаковке",
                "description" => "",
                "pimType" => "integer",
                "ozonType" => "",
                "isRequired" => true,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "dimensions",
                "groupAlias" => "common",
            ],
            "barcode" => [
                "name" => "Штрих код",
                "description" => "",
                "pimType" => "string",
                "ozonType" => "",
                "isRequired" => true,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "publicProductLink" => [
                "name" => "Ссылка на публичную страницу товара ",
                "description" => "",
                "pimType" => "string",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => true,
                "readOnly" => true,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "privateProductLink" => [
                "name" => "Ссылка на товар в личном кабинете",
                "description" => "",
                "pimType" => "string",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => true,
                "readOnly" => true,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "costPrice" => [
                "name" => "Себестоимость товара",
                "description" => "",
                "pimType" => "float",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => true,
                "readOnly" => false,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "productionPrice" => [
                "name" => "Себестоимость производства",
                "description" => "",
                "pimType" => "float",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => true,
                "readOnly" => true,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "productionPriceFlag" => [
                "name" => "Себестоимость производства задана",
                "description" => "если true - цена производства была установлена руками, false - посчитана автоматически",
                "pimType" => "bool",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => true,
                "readOnly" => true,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
            "salePercent" => [
                "name" => "Размер скидки",
                "description" => "",
                "pimType" => "int",
                "ozonType" => "",
                "isRequired" => false,
                "isVisible" => false,
                "readOnly" => true,
                "tabAlias" => "common-information",
                "groupAlias" => "common",
            ],
        ];

        $tabs = $this->attributeTabRepository->finAllWithAliasUuid();

        $group = $this->attributeGroupRepository->findByAlias("common");
        if (is_null($group)) {
            throw new \Exception("group not found");
        }

        foreach ($attributes as $alias => $data) {
            $attribute = $this->attributeRepository->findOneByCriteria(["alias" => $alias]);
            if ($attribute instanceof Attribute) {
                continue;
            }
            $attribute = new Attribute(
                attributeUuid: Uuid::build(),
                catalogUuid: null,
                catalogId: 0,
                typeId: 0,
                attributeId: 0,
                attributeComplexId: 0,
                name: $data["name"],
                description: $data["description"],
                pimType: $data["pimType"],
                type: $data["ozonType"],
                isCollection: false,
                isRequired: $data["isRequired"],
                isAspect: false,
                maxValueCount: 1,
                groupName: '',
                groupId: 0,
                dictionaryId: 0,
                alias: $alias,
                tabUuid: $tabs[$data["tabAlias"]],
                groupUuid: $group->getAttributeGroupUuid(),
                readOnly: $data["readOnly"],
                isVisible: $data["isVisible"],
            );
            $this->entityStoreService->commit($attribute);
        }

        return Command::SUCCESS;
    }
}
