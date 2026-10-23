<?php

namespace Wildberries\Domain\Event;

use Shared\Domain\Event\EventInterface;

class MediaCreated implements EventInterface
{
    public function __construct(
        public string  $mediaId,
        public int     $number,
        public string  $productId,
        public ?string $little,
        public ?string $small,
        public ?string $big,
        public ?string $video,
        public ?string $hash
    )
    {
    }
}
