<?php

declare(strict_types=1);

namespace OneC\Application\Command\CreateNomenclature;

use OneC\Domain\Repository\NomenclatureInterface;
use OneC\Domain\Service\SetBarcodeService;
use OneC\Domain\Service\SetKitService;
use Shared\Domain\Command\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private NomenclatureInterface $nomenclatureRepository,
        private SetBarcodeService     $setBarcodeService,
        private SetKitService         $setKitService,
    ) {
    }

    public function __invoke(Command $command): void
    {
        // проверяем существует номенклатура или нет
        $in1c = $this->nomenclatureRepository->isNomenclatureUpload($command->nomenclatureId);
        // отправляем на создание или обновление
        $this->nomenclatureRepository->pushNomenclature(
            nomenclatureId: $command->nomenclatureId,
            brandId: $command->brandId,
            nomenclatureName: $command->nomenclatureName,
            brandName: $command->brandName,
            vendorCode: $command->vendorCode,
            isKit: $command->isKit,
            isUpdate: $in1c,
            productLineName: $command->productLineName,
        );
        // устанавливаем barcode для номенклатуры
        if (is_string($command->barcode)) {
            $this->setBarcodeService->handel(
                nomenclatureId: $command->nomenclatureId,
                barcode: $command->barcode
            );
        }
        // если товар - комплект, то связваем номенклатуру с товарами
        if ($command->isKit && $command->products) {
            $this->setKitService->handler(
                nomenclatureId: $command->nomenclatureId,
                products: $command->products
            );
        }

    }
}
