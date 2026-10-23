<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Size;
use Wildberries\Domain\Event\SizeCreated;

readonly class CreateSizeListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    )
    {
    }

    public function __invoke(SizeCreated $event): void
    {
        $size = new Size(
            sizeId: $event->sizeId,
            productId: $event->productId,
            chrtId: $event->chrtId,
            techSize: $event->techSize,
            wbSize: $event->wbSize,
            skus: $event->skus
        );

        $this->persistenceRepository->save($size);
    }

}
