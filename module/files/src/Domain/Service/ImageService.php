<?php

namespace Files\Domain\Service;

use Files\Domain\Repository\ImageInterface;

final readonly class ImageService
{
    public function __construct(
        private ImageInterface $imageRepository
    ) {
    }

}
