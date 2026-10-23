<?php

namespace Files\Infrastructure\S3;

use Files\Domain\Repository\ImageInterface;
use Files\Domain\ValueObject\ImageDto;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

final readonly class S3ImageRepository implements ImageInterface
{
    public const string HASH_ALGO = 'sha256';

    public function __construct(
        private FilesystemOperator $defaultStorage
    ) {
    }

    /**
     * @throws FilesystemException
     */
    public function write(
        string $productId,
        string $imageId,
        string $content,
        string $type,
        string $size
    ): void {
        $path = sprintf('%s/%s', $productId, $imageId);
        $hash = hash(self::HASH_ALGO, $path);
        $content = new ImageDto(
            content: $content,
            type: $type,
            size: $size
        );

        $this->defaultStorage->write($hash, (string)$content);
    }

    /**
     * @throws FilesystemException
     * @throws \Exception
     */
    public function read(
        string $productId,
        string $imageId,
    ): ImageDto {

        $path = sprintf('%s/%s', $productId, $imageId);
        $hash = hash(self::HASH_ALGO, $path);
        $file = $this->defaultStorage->read($hash);

        return ImageDto::fromString($file);
    }
}
