<?php

namespace Wildberries\Domain\Repository\Dto;

final class WbAttributeDto
{
    public function __construct(
        public int    $categoryId,
        public int    $parentCategoryId,
        public string $characteristicName,
        /**
         * @var bool Характеристика обязательна к заполенению
         */
        public bool   $isRequired,
        /**
         * @var string Единица имерения (см, гр и т.д.)
         */
        public string $unitName,
        /**
         * @var int Максимальное кол-во значений, которое можно присвоить данной характеристике.
         * Если 0, то нет ограничения.
         */
        public int    $maxCount,
        /**
         * @var bool Характеристика популярна у пользователей
         */
        public bool   $popular,
        /**
         * @var int Тип характеристики (1 и 0 - строка или массив строк; 4 - число или массив чисел)
         */
        public int    $charcType
    ) {
    }
}
