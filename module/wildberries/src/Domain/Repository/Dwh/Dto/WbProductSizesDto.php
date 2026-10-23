<?php

namespace Wildberries\Domain\Repository\Dwh\Dto;

use Wildberries\Domain\Entity\Size;

final class WbProductSizesDto
{
    /**
     * @param string[] $skus
     */
    public function __construct(
        public int     $chrtId,
        public string  $techSize,
        public array   $skus,
        public ?string $wbSize = null
    )
    {
    }

    /**
     * Формирует WbProductSizesDto из json строки
     * @return WbProductSizesDto[]
     * @throws \JsonException
     */
    public static function fromString(string $sizes): array
    {
        $sizesArray = json_decode($sizes, true, 512, JSON_THROW_ON_ERROR);
        $response = [];
        if (is_array($sizesArray)) {
            foreach ($sizesArray as $size) {
                $response[] = new self(
                    chrtId: $size['chrtID'],
                    techSize: $size['techSize'],
                    skus: $size['skus'],
                    wbSize: ($size['wbSize'] == "") ? $size['wbSize'] : null
                );
            }
        }

        return $response;
    }

    /**
     * Формирует WbProductSizesDto[] из Size[]
     * @param Size[] $sizes
     * @return WbProductSizesDto[]
     */
    public static function fromArray(array $sizes): array
    {
        $response = [];
        foreach ($sizes as $size) {
            $response[] = new self(
                chrtId: $size['chrtID'],
                techSize: $size['techSize'],
                skus: $size['skus'],
                wbSize: ($size['wbSize'] == "") ? $size['wbSize'] : null
            );
        }

        return $response;
    }
}
