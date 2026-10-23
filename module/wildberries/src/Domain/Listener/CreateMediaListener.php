<?php

namespace Wildberries\Domain\Listener;

use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Media;
use Wildberries\Domain\Event\MediaCreated;

readonly class CreateMediaListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface $persistenceRepository
    )
    {
    }

    public function __invoke(MediaCreated $event): void
    {
        $media = new Media(
            mediaId: $event->mediaId,
            number: $event->number,
            productId: $event->productId,
            little: $event->little,
            small: $event->small,
            big: $event->big,
            video: $event->video,
            hash: $event->hash
        );

        $this->persistenceRepository->save($media);
    }

}
