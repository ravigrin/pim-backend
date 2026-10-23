<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductMake;

use Exception;
use Pim\Domain\Entity\Product;
use Pim\Domain\Repository\Pim\ProductInterface;
use Pim\Domain\Service\EditProductAttribute;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private ProductInterface     $productRepository,
        private EntityStoreService   $entityStoreService,
        private EditProductAttribute $editProductAttribute,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        $product = $this->productRepository->findOneByCriteria(['productId' => $command->productId]);

        // Создаем / обновляем товар
        if (is_null($product)) {
            $product = new Product(
                productId: $command->productId,
                userId: $command->user->getUserId(),
                vendorCode: $command->vendorCode,
                unitId: $command->unit?->unitId,
                brandId: $command->brand?->brandId,
                productLineId: $command->productLine?->productLineId
            );
        } else {
            $product->setUnitId($command->unit?->unitId);
            $product->setBrandId($command->brand?->brandId);
            $product->setProductLineId($command->productLine?->productLineId);
        }

        // Если товар комплект - связываем с товарами
        if ($command->isKit) {
            $product->setUnification($command->union);
            $product->setIsKit(true);
        }

        // Обновляем связь товара и категорий МП
        if (is_string($command->catalogId)) {
            $product->setCatalogId($command->catalogId);
        }

        // Подготавливаем атрибуты
        foreach ($command->attributes as $attributeData) {
            if (is_string($attributeData->value)) {
                $attributeData->value = [$attributeData->value];
            }
            $this->editProductAttribute->handler(
                productId: $product->getProductId(),
                attributeId: $attributeData->attributeId,
                userId: $command->user->getUserId(),
                value: $attributeData->value
            );
        }

        $this->entityStoreService->commit($product);
    }
}
