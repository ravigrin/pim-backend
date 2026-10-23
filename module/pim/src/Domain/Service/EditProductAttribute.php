<?php

namespace Pim\Domain\Service;

use Pim\Domain\Entity\Attribute;
use Pim\Domain\Entity\ProductAttribute;
use Pim\Domain\Entity\ProductAttributeHistory;
use Pim\Domain\Repository\Internal\OzonModuleInterface;
use Pim\Domain\Repository\Internal\WbModuleInterface;
use Pim\Domain\Repository\Pim\AttributeInterface;
use Pim\Domain\Repository\Pim\ProductAttributeInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Repository\PersistenceInterface;

final readonly class EditProductAttribute
{
    public function __construct(
        private PersistenceInterface      $persistenceRepository,
        private ProductAttributeInterface $productAttributeRepository,
        private AttributeInterface        $attributeRepository,
        private PushProductToOneC         $pushProductToOneC,
        private OzonModuleInterface       $ozonModuleRepository,
        private WbModuleInterface         $wbModuleRepository,
    ) {
    }

    /**
     * @param string[] $value
     */
    public function handler(string $productId, string $attributeId, string $userId, array $value): void
    {
        $productAttribute = $this->productAttributeRepository->findOneByCriteria([
            'productId' => $productId,
            'attributeId' => $attributeId
        ]);

        $oldValue = null;
        if (is_null($productAttribute)) {
            $productAttribute = new ProductAttribute(
                productAttributeId: Uuid::uuid7()->toString(),
                productId: $productId,
                attributeId: $attributeId,
                userId: $userId,
                value: $value
            );
        } else {
            $oldValue = $productAttribute->getValues();
            $productAttribute->setValue($value);
        }
        $this->persistenceRepository->persist($productAttribute);

        // обновляем историю если значения не совпадают
        if (crc32(serialize($value)) !== crc32(serialize($oldValue))) {
            $history = new ProductAttributeHistory(
                productAttributeHistoryId: Uuid::uuid7()->toString(),
                productAttributeId: $productAttribute->getAttributeId(),
                productId: $productId,
                attributeId: $attributeId,
                userId: $userId,
                newValue: $value,
                oldValue: $oldValue,
            );
            $this->persistenceRepository->persist($history);
        }
        $this->persistenceRepository->flush();

        $attribute = $this->attributeRepository->findOneByCriteria([
            'attributeId' => $attributeId
        ]);

        if ($attribute instanceof Attribute) {
            if ($attribute->getAlias() === 'status' && $productAttribute->getValue() === 'В работе') {
                $this->pushProductToOneC->handler($productId);
            }
            if ($attribute->getAlias() === 'status' && $productAttribute->getValue() === 'Маркетплейс') {
                //$this->loggerRepository->info("Push product to ozon: " . $event->productId);
                $this->ozonModuleRepository->pushProduct(
                    $productId
                );
                //$this->loggerRepository->info("Push product to wildberries: " . $event->productId);
                $this->wbModuleRepository->pushProduct(
                    $productId
                );
            }
        }
    }
}
