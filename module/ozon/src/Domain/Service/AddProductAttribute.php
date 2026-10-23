<?php

namespace Ozon\Domain\Service;

use Ozon\Domain\Entity\Attribute;
use Ozon\Domain\Entity\Product;
use Ozon\Domain\Entity\ProductAttribute;
use Ozon\Domain\Entity\ProductAttributeHistory;
use Ozon\Domain\Repository\ProductAttributeInterface;
use Pim\Domain\Entity\User;
use Shared\Domain\Exception\ValueObjectException;
use Shared\Domain\Service\EntityStoreService;
use Shared\Domain\ValueObject\Uuid;

class AddProductAttribute
{
    public function __construct(
        private ProductAttributeInterface $productAttributeRepository,
        private BuildPrepareValue         $buildPrepareValue,
        private EntityStoreService        $entityStoreService,
    ) {
    }

    /**
     * @param Product $product
     * @param Attribute $attribute
     * @param User $user
     * @param array $value
     * @param array $prepareValue
     * @return void
     * @throws ValueObjectException
     */
    public function handler(
        Product   $product,
        Attribute $attribute,
        User      $user,
        array     $value,
        array     $prepareValue
    ): void {
        $productAttribute = $this->productAttributeRepository->findOneByCriteria([
            'attributeUuid' => $attribute->getAttributeUuid(),
            'productUuid' => $product->getProductUuid()
        ]);
        if (is_null($productAttribute)) {
            $productAttribute = new ProductAttribute(
                productAttributeUuid: Uuid::build(),
                attributeUuid: $attribute->getAttributeUuid(),
                productUuid: $product->getProductUuid(),
                userUuid: new Uuid($user->getUserId()),
                value: $value,
                prepareValue: $prepareValue
            );
        } else {
            $oldValue = $productAttribute->getValues();
            // обновляем историю если значения не совпадают
            if (crc32(serialize($value)) !== crc32(serialize($oldValue))) {
                $history = new ProductAttributeHistory(
                    productAttributeHistoryUuid: Uuid::build(),
                    productAttributeUuid: $productAttribute->getProductAttributeUuid(),
                    productUuid: $product->getProductUuid(),
                    attributeUuid: $attribute->getAttributeUuid(),
                    userUuid: Uuid::fromString($user->getUserId()),
                    newValue: $value,
                    oldValue: $oldValue
                );
                $this->entityStoreService->commit($history);
            }
            $productAttribute->setValue($value);
            $prepareValue = $this->buildPrepareValue->build(
                catalogId: $product->getCatalogUuid(),
                attribute: $attribute,
                values: $value
            );
            $productAttribute->setPrepareValue($prepareValue);
        }
        $this->entityStoreService->commit($productAttribute);
    }
}
