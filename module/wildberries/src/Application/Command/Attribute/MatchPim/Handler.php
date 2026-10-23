<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Attribute\MatchPim;

use Pim\Application\Query\Attribute\GetFullByAlias\Query as PimGetFullByAlias;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Query\QueryBusInterface;
use Shared\Infrastructure\PersistenceRepository;
use Wildberries\Domain\Entity\AttributeMap;
use Wildberries\Domain\Repository\AttributeInterface;
use Wildberries\Domain\Repository\AttributeMapInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

final readonly class Handler implements CommandHandlerInterface
{
    /**
     * @var array{string: string}
     */
    private const array WB_PIM_MAPPING = [
        'Наименование' => 'name',
        'SKU' => 'barcode',
        'Описание' => 'description',
        'Комплектация' => 'packageBundle',
        'Состав' => 'structure',
        'Упаковка' => 'packaging',
        'Объем товара' => 'volume',
        'Срок годности' => 'expirationDate',
        'РРЦ' => 'RecommendRetailPrice',   // РРЦ - не атрибут вб - добавленный
        // Доработка (см. TODO.md): конвертация мм -> см при маппинге упаковочных размеров.
        'Длина упаковки' => 'packageLength',
        'Ширина упаковки' => 'packageWidth',
        'Высота упаковки' => 'packageHeight',
        'Вес товара с упаковкой (г)' => 'packageWeight',
        'Вес товара без упаковки (г)' => 'weight'
    ];


    public function __construct(
        private LoggerInterface       $logger,
        private QueryBusInterface     $queryBus,
        private AttributeMapInterface $attributeMapRepository,
        private AttributeInterface    $attributeRepository,
        private PersistenceRepository $persistenceRepository
    ) {
    }

    public function __invoke(Command $command): void
    {
        foreach (self::WB_PIM_MAPPING as $wbName => $pimAlias) {
            // Если маппинг уже есть - пропускаем - обновления нет
            if ($this->attributeMapRepository->findOneBy(['pimAlias' => $pimAlias])) {
                continue;
            }
            if (!$wbAttribute = $this->attributeRepository->findOneBy(['name' => $wbName])) {
                $this->logger->critical("NOT FOUND WB Attribute by name: {$wbName}
                - src/Wildberries/Application/Command/Attribute/MatchPim/Handler.php::invoke()");
            }

            if (!$pimAttribute = $this->queryBus->dispatch(new PimGetFullByAlias($pimAlias))) {
                $this->logger->critical("Not FOUND PIM Product Status by ID: $pimAlias
                - src/Wildberries/Application/Command/Attribute/MatchPim/Handler.php::invoke()");
            }

            $map = new AttributeMap(
                attributeMapId: Uuid::uuid7()->toString(),
                wbAttributeId: $wbAttribute->getAttributeId(),
                pimAttributeId: $pimAttribute->getAttributeId(),
                wbName: $wbName,
                pimAlias: $pimAlias,
                wbMeasure: $wbAttribute->getMeasurement(),
                pimMeasure: $pimAttribute->getMeasurement()
            );

            $this->persistenceRepository->persist($map);
        }
        $this->persistenceRepository->flush();
    }

}
