<?php

namespace Files\Domain\Repository;

use Files\Domain\ValueObject\ImageDto;
use League\Flysystem\FilesystemException;

interface ImageInterface
{
    /**
     * @throws FilesystemException
     */
    public function write(
        string $productId,
        string $imageId,
        string $content,
        string $type,
        string $size
    ): void;

    /**
     * @throws FilesystemException
     */
    public function read(
        string $productId,
        string $imageId,
    ): ImageDto;

}
