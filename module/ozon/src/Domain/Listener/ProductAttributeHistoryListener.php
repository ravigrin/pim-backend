<?php

namespace Ozon\Domain\Listener;

use Ozon\Domain\Event\ProductAttributeHistoryAdded;
use Ozon\Domain\Repository\ProductAttributeInterface;
use Ozon\Domain\Repository\ProductInterface;
use Psr\Log\LoggerInterface;
use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Exception\ValueObjectException;
use Shared\Domain\Repository\PersistenceInterface;

readonly class ProductAttributeHistoryListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface      $persistenceRepository,
        private ProductInterface          $productRepository,
        private ProductAttributeInterface $productAttribute,
        private LoggerInterface           $logger,
    ) {
    }

    /**
     * @throws ValueObjectException
     */
    public function __invoke(ProductAttributeHistoryAdded $event): void
    {
        $product = $this->productRepository->findOneByCriteria(['uuid' => $event->productId]);
        if (is_null($product)) {
            $this->logger->warning('Ozon product not found for attribute history event.', [
                'productId' => (string) $event->productId,
            ]);
            return;
        }

        $productAttribute = $this->productAttribute->findOneByCriteria([
            'productUuid' => $event->productId,
            'attributeUuid' => $event->attributeId
        ]);

        if (is_null($productAttribute)) {
            $this->logger->warning('Ozon product attribute not found for attribute history event.', [
                'productId' => (string) $event->productId,
                'attributeId' => (string) $event->attributeId,
            ]);
            return;
        }

    }
}
