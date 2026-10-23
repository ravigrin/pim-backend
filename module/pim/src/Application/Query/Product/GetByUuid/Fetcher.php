<?php

declare(strict_types=1);

namespace Pim\Application\Query\Product\GetByUuid;

use Pim\Application\Query\Product\ProductFullDto;
use Pim\Domain\Entity\ProductAttribute;
use Pim\Domain\Repository\Internal\OzonModuleInterface;
use Pim\Domain\Repository\Internal\WbModuleInterface;
use Pim\Domain\Repository\Pim\ProductAttributeInterface;
use Pim\Domain\Repository\Pim\ProductInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductInterface          $productRepository,
        private ProductAttributeInterface $productAttributeRepository,
        private OzonModuleInterface       $ozonModuleRepository,
        private WbModuleInterface         $wbModuleRepository,
    ) {
    }

    public function __invoke(Query $query): ?ProductFullDto
    {
        $product = $this->productRepository->findOneByCriteria([
            "productId" => $query->productId
        ]);

        if (is_null($product)) {
            return null;
        }

        $attributes = array_map(
            fn (ProductAttribute $productAttribute): array => [
                'attributeId' => $productAttribute->getAttributeId(),
                'value' => $productAttribute->getValue()
            ],
            $this->productAttributeRepository->findByCriteria(['productId' => $product->getProductId()])
        );

        $ozonStatus = $this->ozonModuleRepository->getOzonStatus($product->getProductId());
        $wbStatus = $this->wbModuleRepository->getProductStatus($product->getProductId());

        return ProductFullDto::getDto($product, $attributes, $ozonStatus, $wbStatus);
    }
}
