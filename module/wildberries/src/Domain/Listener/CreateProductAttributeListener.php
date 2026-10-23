<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\ProductAttribute;
use Wildberries\Domain\Event\ProductAttributeCreated;

readonly class CreateProductAttributeListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    )
    {
    }

    public function __invoke(ProductAttributeCreated $event): void
    {
        $catalog = new ProductAttribute(
            productAttributeId: $event->productAttributeId,
            productId: $event->productId,
            attributeId: $event->productId,
            value: $event->value,
            hash: $event->hash,
            wbAttributeId: $event->wbAttributeId
        );

        $this->persistenceRepository->save($catalog);
    }

}
