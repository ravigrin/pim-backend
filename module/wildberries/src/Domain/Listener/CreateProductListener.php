<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Product;
use Wildberries\Domain\Event\ProductCreated;

readonly class CreateProductListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    )
    {
    }

    public function __invoke(ProductCreated $event): void
    {
        $product = new Product(
            productId: $event->productId,
            exportStatus: $event->exportStatus,
            catalogId: $event->catalogId,
            imtId: $event->imtId,
            nmId: $event->nmId,
            vendorCode: $event->vendorCode,
            brand: $event->brand,
            title: $event->title,
            description: $event->description,
            sellerName: $event->sellerName,
            nmUuid: $event->nmUuid,
            dimensions: $event->dimensions,
            tags: $event->tags
        );

        $this->persistenceRepository->save($product);
    }

}
