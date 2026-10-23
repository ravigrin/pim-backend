<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductsMake;

use Pim\Application\Query\Brand\BrandDto;
use Pim\Application\Query\ProductLine\ProductLineDto;
use Pim\Application\Query\Unit\UnitDto;
use Pim\Domain\Entity\Product;
use Pim\Domain\Repository\Pim\AttributeInterface;
use Pim\Domain\Repository\Pim\BrandInterface;
use Pim\Domain\Repository\Pim\ProductInterface;
use Pim\Domain\Repository\Pim\ProductLineInterface;
use Pim\Domain\Repository\Pim\UnitInterface;
use Pim\Domain\Service\EditProductAttribute;
use Pim\Domain\Service\GenerateSku;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    private const array ALLOW_STATUSES = ['Черновик', 'В работе'];

    private const string DEFAULT_STATUS = 'Черновик';

    public function __construct(
        private LoggerInterface      $logger,
        private GenerateSku          $generateSku,
        private UnitInterface        $unitRepository,
        private BrandInterface       $brandRepository,
        private ProductLineInterface $productLineRepository,
        private ProductInterface     $productRepository,
        private AttributeInterface   $attributeRepository,
        private EntityStoreService   $entityStoreService,
        private EditProductAttribute $editProductAttribute,
    ) {
    }

    /**
     * @param Command $command
     * @return void
     */
    public function __invoke(Command $command): void
    {
        $unit = $this->unitRepository->findOneByCriteria(["code" => '0' . $command->unit]);
        if (!$unit) {
            $this->logger->critical("Not Found Unit: '$command->unit' - Pim/Application/Command/NomenclatureMake/Handler.php");
            return;
        }

        $brand = $this->brandRepository->findOneByCriteria(["name" => $command->brand]);
        if (!$brand) {
            $this->logger->critical("Not Found Brand: '$command->brand' - Pim/Application/Command/NomenclatureMake/Handler.php");
            return;
        }

        if ($command->productLine) {
            $productLine = $this->productLineRepository->findOneByCriteria(["name" => $command->productLine]);
            if (!$productLine) {
                $this->logger->critical("Not Found Product Line: '$command->productLine' - Pim/Application/Command/NomenclatureMake/Handler.php");
                return;
            }
        }

        $vendorCodeAttribute = $this->attributeRepository->findOneByCriteria(["alias" => 'vendorCode']);

        if ($command->vendorCode) {
            // Если у артикула установлен флаг обновления 'U_' - обновляем товар
            if (str_contains($command->vendorCode, 'U_')) {
                $vendorCode = preg_replace('/U_/', '', $command->vendorCode);
                $product = $this->productRepository->findOneByCriteria(['vendorCode' => $vendorCode]);
                if (!$product) {
                    $this->logger->critical("Not Found Product by article: '$vendorCode' - Pim/Application/Command/NomenclatureMake/Handler.php");
                    return;
                }
            } else {
                // Иначе создаем новый товар с переданным артикулом
                $product = $this->productRepository->findOneByCriteria(['vendorCode' => $command->vendorCode]);
                if ($product) {
                    $this->logger->critical("Product with article: '$command->vendorCode' already exists - Pim/Application/Command/NomenclatureMake/Handler.php");
                    return;
                }

                $product = new Product(
                    productId: Uuid::uuid7()->toString(),
                    userId: $command->user->getUserId(),
                    vendorCode: $command->vendorCode,
                    unitId: $unit->getId(),
                    brandId: $brand->getBrandId(),
                    productLineId: isset($productLine) ? $productLine->getProductLineId() : null,
                    isKit: $command->isKit
                );

                if ($vendorCodeAttribute) {
                    $this->editProductAttribute->handler(
                        $product->getProductId(),
                        $vendorCodeAttribute->getAttributeId(),
                        $command->user->getUserId(),
                        [$command->vendorCode]
                    );
                    $this->editProductAttribute->handler(
                        $product->getProductId(),
                        $vendorCodeAttribute->getAttributeId(),
                        $command->user->getUserId(),
                        [$command->vendorCode]
                    );
                }
            }
        } else {
            // Если артикул не передан - создаем новый товар с новым артикулом
            $vendorCode = $this->generateSku->build(
                unit: UnitDto::getDto($unit),
                brand: BrandDto::getDto($brand),
                productLine: isset($productLine) ? ProductLineDto::getDto($productLine) : null,
                isKit: $command->isKit
            );
            $product = new Product(
                productId: Uuid::uuid7()->toString(),
                userId: $command->user->getUserId(),
                vendorCode: $vendorCode,
                unitId: $unit->getId(),
                brandId: $brand->getBrandId(),
                productLineId: isset($productLine) ? $productLine->getProductLineId() : null,
                isKit: $command->isKit
            );

            if ($vendorCodeAttribute) {
                $this->editProductAttribute->handler(
                    $product->getProductId(),
                    $vendorCodeAttribute->getAttributeId(),
                    $command->user->getUserId(),
                    [$vendorCode]
                );
            }
        }

        // Добавляем атрибут Наименование
        $nameAttribute = $this->attributeRepository->findOneByCriteria(['alias' => 'name']);
        if ($nameAttribute) {
            $this->editProductAttribute->handler(
                $product->getProductId(),
                $nameAttribute->getAttributeId(),
                $command->user->getUserId(),
                [$command->name]
            );
        }

        // Добавляем атрибут Статус
        if (!$command->status || !in_array($command->status, self::ALLOW_STATUSES, true)) {
            $command->status = self::DEFAULT_STATUS;
        }
        $statusAttribute = $this->attributeRepository->findOneByCriteria(['alias' => 'status']);
        if ($statusAttribute) {
            $this->editProductAttribute->handler(
                $product->getProductId(),
                $statusAttribute->getAttributeId(),
                $command->user->getUserId(),
                [$command->status]
            );
        }

        $this->entityStoreService->commit($product);
    }
}
