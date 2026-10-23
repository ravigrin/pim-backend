<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Product\GetByUuid;

use Ozon\Application\Query\Product\ProductFullDto;
use Ozon\Domain\Entity\ProductAttribute;
use Ozon\Domain\Repository\AttributeBridgeInterface;
use Ozon\Domain\Repository\Internal\PimModuleInterface;
use Ozon\Domain\Repository\ProductAttributeInterface;
use Ozon\Domain\Repository\ProductInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductInterface          $productRepository,
        private ProductAttributeInterface $productAttributeRepository,
        private PimModuleInterface        $pimModuleRepository,
        private AttributeBridgeInterface  $attributeBridgeRepository,
    ) {
    }

    public function __invoke(Query $query): ?ProductFullDto
    {
        $product = $this->productRepository->findOneByCriteria([
            'productUuid' => $query->productId
        ]);

        // если товара не существует, создаем загрушку для автозаполнения атрибутов
        if (is_null($product)) {

            $attributes = [];

            // загружаем атрибуты товара pim и по связи добавляем в загрушку товара
            $pimAttributes = $this->pimModuleRepository->findAttributeByProduct($query->productId);
            foreach ($pimAttributes as $uuid => $value) {
                $bridge = $this->attributeBridgeRepository->findOneByCriteria(["attributePimUuid" => $uuid]);
                if (is_null($bridge)) {
                    continue;
                }
                $attributes[] = [
                    "attributeId" => $bridge->getAttributeId(),
                    "value" => $value
                ];
            }

            // добавления бренда (его нет в атрибутах pim)
            //            $brandAttribute = $this->attributeRepository->findByExternalId(85);
            //            $brandName = $this->pimModuleRepository->findBrandValueByProduct($query->productId);
            //            if ($brandAttribute && $brandName) {
            //                $attributes[] = [
            //                    "attributeId" => $brandAttribute->getAttributeId(),
            //                    "value" => $brandName
            //                ];
            //            }

            return ProductFullDto::getDto(
                productId: $query->productId,
                attributes: $attributes
            );
        }
        // тут товар существует, обычная загрузка всех атрибутов
        $attributes = array_map(
            fn (ProductAttribute $productAttribute): array => [
                'attributeId' => $productAttribute->getAttributeUuid()->getValue(),
                'value' => $productAttribute->getValue()
            ],
            $this->productAttributeRepository->findByCriteria(['productUuid' => $product->getProductUuid()])
        );

        return ProductFullDto::getDto(
            productId: $product->getProductUuid()->getValue(),
            catalogId: $product->getCatalogUuid()?->getValue(),
            union: $product->getUnification(),
            attributes: $attributes
        );
    }
}
