<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Entity\Attribute;
use Pim\Domain\Repository\Pim\AttributeInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:attribute:import',
    description: 'Загрузка атрибутов',
)] final class AttributeImportCommand extends Command
{
    public function __construct(
        private EntityStoreService $entityStoreService,
        private AttributeInterface $attributeRepository,
        string                     $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /*
            bool
            datetime
            float
            integer
            integer[]
            string
            string[]
         */

        $attributes = [
            'name' => ['Название', 'string', 1],
            'shortName' => ['Короткое имя', 'string', 1],
            'vendorCode' => ['Артикул', 'string', 1],
            'barcode' => ['Штрих код', 'string', 1],
            'contractManagement' => ['Контрактный менеджмент', 'string', 1],
            'status' => ['Статус', 'string', 1],
            'contentLink' => ['Ссылка на папку с контентом', 'string', 1],
            'description' => ['Описание', 'string', 1],
            'usage' => ['Способ применения', 'string', 1],
            'structure' => ['Состав', 'string[]', 45],
            'structureRu' => ['Состав на русском', 'string[]', 45],
            'packaging' => ['Тара/Упаковка', 'string', 1],
            'volume' => ['Объём товара, мл', 'integer', 1, 'мл'],
            'expirationDate' => ['Срок годности', 'integer', 1],
            'costPrice' => ['Себестоимость (руб)', 'float', 1, 'руб'],
            'RecommendRetailPrice' => ['Рекомендуемая розничная цена', 'float', 1, 'руб'],
            'packageLength' => ['Длина упаковки, мм', 'integer', 1, 'мм'],
            'packageWidth' => ['Ширина упаковки, мм', 'integer', 1, 'мм'],
            'packageHeight' => ['Высота упаковки, мм', 'integer', 1, 'мм'],
            'packageWeight' => ['Вес с упаковкой, г', 'integer', 1, 'г'],
            'weight' => ['Вес, г', 'integer', 1, 'г'],
            'packageBundle' => ['Комплектация', 'string[]', 12],
            'dateStartSaleOzon' => ['Дата старта продаж озон', 'datetime', 1],
            'dateStartSaleWB' => ['Дата старта продаж вб', 'datetime', 1],
            'dateSigningSpecification' => ['Дата подписания спецификации', 'datetime', 1],
            'dateReceiptWarehouse' => ['Дата поступления на склад', 'datetime', 1],
            'datePolygraphyLayout' => ['Дата полиграфия верстка', 'datetime', 1],
            'datePolygraphyProofreading' => ['Дата полиграфия вычитка', 'datetime', 1],
            'datePolygraphyTransferred' => ['Дата полиграфия передано', 'datetime', 1],
            'dateCardsLayout' => ['Дата карточки верстка', 'datetime', 1],
            'dateCardProofreading' => ['Дата карточки вычитка', 'datetime', 1],
            'dateCardsTransferred' => ['Дата карточки передано', 'datetime', 1],
        ];

        foreach ($attributes as $alias => $data) {
            $attribute = $this->attributeRepository->findOneByCriteria(["alias" => $alias]);
            if (is_null($attribute)) {
                $attribute = new Attribute(
                    attributeId: Uuid::uuid7()->toString(),
                    name: $data[0],
                    alias: $alias,
                    pimType: $data[1],
                    maxCount: $data[2],
                    measurement: $data[3] ?? null
                );
            }
            if (isset($data[3])) {
                $attribute->setMeasurement($data[3]);
            }
            $this->entityStoreService->commit($attribute);
        }

        return Command::SUCCESS;
    }

}
