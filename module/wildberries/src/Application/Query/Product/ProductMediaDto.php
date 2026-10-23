<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product;

use Wildberries\Domain\Entity\Media;

/**
 * DTO медиафайлов карточки товара Wildberries
 */
final class ProductMediaDto
{
    /**
     * @param ProductPhotoItemDto[]|null $photo
     * @param string|null $video
     */
    public function __construct(
        public ?array  $photo = null,
        public ?string $video = null
    )
    {
    }

    /**
     * @param Media[] $medias
     * @return self
     */
    public static function getDto(array $medias): self
    {
        $photos = $video = null;

        foreach ($medias as $media) {
            if ($video = $media->getVideo()) {
                continue;
            }
            $photos[] = new ProductPhotoItemDto(
                little: $media->getLittle(),
                small: $media->getSmall(),
                big: $media->getBig()
            );
        }

        return new self(
            photo: $photos,
            video: $video
        );
    }
}
