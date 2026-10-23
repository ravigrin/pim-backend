<?php

namespace Wildberries\Application\Query\Product;

use Wildberries\Domain\Entity\Size;

final class ProductSizeDto
{
    /**
     * Массив размеров для номенклатуры
     * (для безразмерного товара все равно нужно передавать данный массив без параметров (wbSize и techSize),
     * но с ценой и баркодом)
     * @param string[] $skus
     */
    public function __construct(
        public string $techSize,
        public string $wbSize,
        public int    $chrtId,
        public array  $skus
    )
    {
    }

    /**
     * @param Size[] $sizes
     * @return self[]|null
     */
    public static function getDto(array $sizes): ?array
    {
        foreach ($sizes as $size) {
            $response[] = new self(
                techSize: $size->getTechSize(),
                wbSize: $size->getWbSize(),
                chrtId: $size->getChrtId(),
                skus: $size->getSkus()
            );
        }

        return $response ?? null;
    }
}
