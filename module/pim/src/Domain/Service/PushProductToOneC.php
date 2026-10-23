<?php

namespace Pim\Domain\Service;

use Pim\Domain\Repository\Internal\OneCModuleInterface;
use Pim\Domain\Repository\Pim\BrandInterface;
use Pim\Domain\Repository\Pim\ProductAttributeInterface;
use Pim\Domain\Repository\Pim\ProductInterface;
use Pim\Domain\Repository\Pim\ProductLineInterface;

class PushProductToOneC
{
    public function __construct(
        private ProductInterface          $productRepository,
        private OneCModuleInterface       $oneCModule,
        private ProductAttributeInterface $productAttributeRepository,
        private BrandInterface            $brandRepository,
        private ProductLineInterface      $productLineRepository,
    ) {
    }

    public function handler(string $productId): void
    {
        $product = $this->productRepository->findOneByCriteria(['productId' => $productId]);
        if (is_null($product)) {
            return;
        }

        if (is_null($product->getBrandId()) || is_null($product->getVendorCode())) {
            return;
        }

        $barcode = $this->productAttributeRepository->findProductAttribute(
            productId: $product->getProductId(),
            alias: 'barcode'
        );
        if (is_null($barcode)) {
            return;
        }

        $name = $this->productAttributeRepository->findProductAttribute(
            productId: $product->getProductId(),
            alias: 'name'
        );

        $brand = $this->brandRepository->findOneByCriteria(['brandId' => $product->getBrandId()]);
        if (is_null($brand)) {
            return;
        }

        $productLine = $this->productLineRepository->findOneByCriteria(
            ['productLineId' => $product->getProductLineId()]
        );

        $this->oneCModule->pushProduct(
            nomenclatureId: $product->getProductId(),
            brandId: $product->getBrandId(),
            nomenclatureName: $name->getValue(),
            brandName: $brand->getName(),
            vendorCode: $product->getVendorCode(),
            isKit: $product->isKit(),
            products: $product->getUnification(),
            barcode: $barcode->getValue(),
            productLineName: $productLine?->getName()
        );
    }
}
