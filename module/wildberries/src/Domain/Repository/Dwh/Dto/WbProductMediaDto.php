<?php

namespace Wildberries\Domain\Repository\Dwh\Dto;

final class WbProductMediaDto
{
    public function __construct(
        public ?string $little = null,
        public ?string $small = null,
        public ?string $big = null,
        public ?string $video = null,
    )
    {
    }

    /**
     * Формирует массив WbProductMediaDto из json строки
     * @return WbProductMediaDto[]
     * @throws \JsonException
     */
    public static function fromString(?string $photos = null, ?string $video = null): array
    {
        $response = [];

        if ($photos) {
            $photos = json_decode($photos, true, 512, JSON_THROW_ON_ERROR);
            foreach ($photos as $photo) {
                $response[] = new self(
                    little: $photo['516x288'],
                    small: $photo['small'],
                    big: $photo['big']
                );
            }
        }

        if ($video) {
            $response[] = new self(
                video: $video
            );
        }

        return $response;
    }
}
