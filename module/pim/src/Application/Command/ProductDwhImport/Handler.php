<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductDwhImport;

use Pim\Domain\Entity\Brand;
use Pim\Domain\Entity\Product;
use Pim\Domain\Entity\ProductLine;
use Pim\Domain\Repository\Dwh;
use Pim\Domain\Repository\Pim;
use Pim\Domain\Repository\Pim\UserInterface;
use Pim\Domain\Service\EditProductAttribute;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private Dwh\ProductInterface     $dwhProductRepository,
        private Pim\ProductInterface     $productRepository,
        private Pim\AttributeInterface   $attributeRepository,
        private PersistenceInterface     $persistenceRepository,
        private EditProductAttribute     $editProductAttribute,
        private Pim\BrandInterface       $brandRepository,
        private Pim\ProductLineInterface $productLineRepository,
        private Pim\UnitInterface        $unitRepository,
        private UserInterface            $userRepository,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findByUsername('system');
        if (is_null($user)) {
            throw new \Exception('User system not found');
        }

        $nameAttribute = $this->attributeRepository->findOneByCriteria(['alias' => 'name']);
        if (is_null($nameAttribute)) {
            throw new \Exception('Attribute name not found');
        }

        $statusAttribute = $this->attributeRepository->findOneByCriteria(['alias' => 'status']);
        if (is_null($statusAttribute)) {
            throw new \Exception('Attribute status not found');
        }

        $brands = $this->brandRepository->findByCriteria([]);
        $brands = array_map(fn (Brand $brand) => $brand->getName(), $brands);

        $productLines = $this->productLineRepository->findByCriteria([]);
        $productLines = array_map(fn (ProductLine $productLine) => $productLine->getName(), $productLines);

        $filtrate = array_merge($brands, $productLines);

        $unitsEntity = $this->unitRepository->findByCriteria([]);
        $units = [];
        foreach ($unitsEntity as $unitEntity) {
            $units[$unitEntity->getCode()] = $unitEntity->getId();
        }

        $productLinesEntity = $this->productLineRepository->findByCriteria([]);
        $productLines = [];
        foreach ($productLinesEntity as $productLineEntity) {
            $productLines[crc32($productLineEntity->getBrandId().$productLineEntity->getCode())] = $productLineEntity->getProductLineId();
        }

        $dwhProducts = $this->dwhProductRepository->findAll();
        foreach ($dwhProducts as $dwhProduct) {

            $localProduct = $this->productRepository->findOneByCriteria(['productId' => $dwhProduct['productGuid']]);
            if ($localProduct instanceof Product) {
                continue;
            }

            $productName = $dwhProduct['productName'];
            foreach ($filtrate as $item) {
                $productName = str_replace($item, '', $productName);
            }

            $unitCode = substr($dwhProduct['vendorCode'], 0, 2);
            $productLineCode = substr($dwhProduct['vendorCode'], 5, 3);

            $product = new Product(
                productId: $dwhProduct['productGuid'],
                userId: $user->getUserId(),
                vendorCode: $dwhProduct['vendorCode'],
                catalogId: null,
                unitId: $units[$unitCode],
                brandId: $dwhProduct['brandGuid'],
                productLineId: $productLines[crc32($dwhProduct['brandGuid'].$productLineCode)] ?? null,
                isKit: !(($dwhProduct['type'] === 'Продукция'))
            );

            $this->editProductAttribute->handler(
                productId: $product->getProductId(),
                attributeId: $nameAttribute->getAttributeId(),
                userId: $user->getUserId(),
                value: [trim($productName)]
            );

            $this->editProductAttribute->handler(
                productId: $product->getProductId(),
                attributeId: $statusAttribute->getAttributeId(),
                userId: $user->getUserId(),
                value: ['В работе']
            );

            $this->persistenceRepository->persist($product);
        }
        //$this->persistenceRepository->flush();
    }
}
