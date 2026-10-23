<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Product\Make;

use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Query\QueryBusInterface;
use Shared\Domain\Service\EntityStoreService;
use Pim\Application\Query\Product\GetStatusByUuid\Query as GetStatusByUuid;
use Pim\Application\Query\Product\GetVendorCodeByUuid\Query as GetVendorCodeByUuid;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Entity\Product;
use Wildberries\Domain\Repository\AttributeInterface;
use Wildberries\Domain\Repository\ProductInterface;
use Wildberries\Infrastructure\Service\ProductAttributeService;
use Psr\Log\LoggerInterface;

/**
 * Класс для обработки комманды сохранения товара Wildberries (создание и редактирование)
 */
final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private LoggerInterface         $logger,
        private QueryBusInterface       $queryBus,
        private ProductInterface        $productRepository,
        private EntityStoreService      $entityStoreService,
        private ProductAttributeService $productAttributeService,
        private AttributeInterface      $attributeRepository
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Command $command): void
    {
        $product = $this->productRepository->findOneBy(['productId' => $command->productId]);

        if (!$statusString = $this->queryBus->dispatch(new GetStatusByUuid($command->productId))) {
            $this->logger->critical("Not FOUND PIM Product Status by ID: $command->productId
                - Wildberries/Application/Command/Product/Make/Handler.php::invoke()");
            $statusString = 'Черновик';
        }

        // Доработка (см. TODO.md): если статус 1 - сразу валидировать для МП и отправлять.
        //        * 0 - новый / измененный
        //        * 1 - отправка
        //        * 2 - отправлено (маркетплейс)
        //        * 3 - ошибка
        $status = match ($statusString) {
            default => 0,
            'Маркетплейс', 'Продажи' => 1
        };

        // $brand = '';
        // Бренд нужно матчить с тем как они указаны в вб

        // $barcode = '';
        // $sizes = ["techSize" => "0", "skus" => [$barcode]];
        // Взять из пима

        if (!$product) {
            if (!$vendorCode = $this->queryBus->dispatch(new GetVendorCodeByUuid($command->productId))) {
                $this->logger->critical("Not FOUND PIM Product ID: $command->productId
                - Wildberries/Application/Command/Product/Make/Handler.php::invoke()");
            }

            $product = new Product(
                productId: $command->productId,
                exportStatus: $status,
                catalogId: $command->catalogId,
                vendorCode: $vendorCode ?? null
            );
        } else {
            $product->setExportStatus($status);
        }
        // Сохраняем товар
        $this->entityStoreService->commit($product);

        // Сохраняем атрибуты товара
        $attributes = $command->attributes;
        foreach ($attributes as $attribute) {
            $localAttribute = $this->attributeRepository->findOneBy(
                ['attributeId' => $attribute['attributeId']]
            );

            if (!$localAttribute instanceof Attribute) {
                $this->logger->info('WB: attribute not found locally, skipped.', [
                    'attributeId' => $attribute['attributeId'],
                    'productId' => (string) $command->productId,
                ]);
                continue;
            }

            $value = is_scalar($attribute['value'])
                ? [$attribute['value']]
                : json_encode($attribute['value'], JSON_THROW_ON_ERROR);

            $this->productAttributeService->handling(
                $command->user,
                $product,
                $localAttribute,
                $value
            );
        }

        // Заполняем объединенные товары
        $unionIds = $command->union;
        $unionIds[] = $command->productId;
        if (!empty($command->union)) {
            $this->productAttributeService->fillUnion($command->user, $unionIds);
        }

    }

}
