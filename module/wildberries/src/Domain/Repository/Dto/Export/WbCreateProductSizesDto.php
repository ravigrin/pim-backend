<?php

namespace Wildberries\Domain\Repository\Dto\Export;

final class WbCreateProductSizesDto
{
    /**
     * Массив размеров для номенклатуры (для безразмерного товара все равно нужно передавать данный массив без параметров (wbSize и techSize), но с ценой и баркодом)
     * @param string[] $skus
     */
    public function __construct(
        public string $techSize,
        public string $wbSize,
        public int    $price,
        public array  $skus
    ) {
    }
}
