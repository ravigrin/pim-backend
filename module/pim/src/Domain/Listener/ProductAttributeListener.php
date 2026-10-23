<?php

namespace Pim\Domain\Listener;

use Pim\Domain\Entity\Attribute;
use Pim\Domain\Entity\ProductAttribute;
use Pim\Domain\Entity\ProductAttributeHistory;
use Pim\Domain\Event\ProductAttributeAdded;
use Pim\Domain\Repository\Pim\AttributeInterface;
use Pim\Domain\Repository\Pim\ProductAttributeInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;

readonly class ProductAttributeListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface      $persistenceRepository,
        private AttributeInterface        $attributeRepository,
        private ProductAttributeInterface $productAttributeRepository,
        private LoggerInterface           $loggerRepository,
    ) {
    }

    public function __invoke(ProductAttributeAdded $event): void
    {
        $productAttribute = $this->productAttributeRepository->findOneByCriteria([
            'productId' => $event->productId,
            'attributeId' => $event->attributeId
        ]);

        $oldValue = null;
        if (is_null($productAttribute)) {
            $productAttribute = new ProductAttribute(
                productAttributeId: Uuid::uuid7()->toString(),
                productId: $event->productId,
                attributeId: $event->attributeId,
                userId: $event->userId,
                value: $event->value
            );
        } else {
            $oldValue = $productAttribute->getValues();
            $productAttribute->setValue($event->value);
        }
        $this->persistenceRepository->save($productAttribute);

        // обновляем историю если значения не совпадают
        if (crc32(serialize($event->value)) !== crc32(serialize($oldValue))) {
            $history = new ProductAttributeHistory(
                productAttributeHistoryId: Uuid::uuid7()->toString(),
                productAttributeId: $productAttribute->getAttributeId(),
                productId: $event->productId,
                attributeId: $event->attributeId,
                userId: $event->userId,
                newValue: $event->value,
                oldValue: $oldValue,
            );
            $this->persistenceRepository->save($history);
        }

        $attribute = $this->attributeRepository->findOneByCriteria([
            'attributeId' => $event->attributeId
        ]);

        if ($attribute instanceof Attribute) {
            if ($attribute->getAlias() === 'status' && $productAttribute->getValue() === 'В работе') {
                $this->loggerRepository->info("Push product to 1c: " . $event->productId);
                $this->pushService->pushToOneC($event->productId);
            }
            if ($attribute->getAlias() === 'status' && $productAttribute->getValue() === 'Маркетплейс') {
                // надо подождать пока все данные сохраняться в базе
                sleep(10);

                //$this->loggerRepository->info("Push product to ozon: " . $event->productId);
                $this->pushService->pushOzonProduct(
                    $event->productId
                );
                //$this->loggerRepository->info("Push product to wildberries: " . $event->productId);
                $this->pushService->pushWildberriesProduct(
                    $event->productId
                );
            }
        }
    }
}
