<?php

declare(strict_types=1);

namespace Wildberries\Domain\Helper;

final class TypeResolver
{
    /**
     * Преобразует числовой тип атрибута Wildberries в строковый тип
     * @param int $charcType
     * @param int $maxCount
     * @return string
     */
    public function getType(int $charcType, int $maxCount): string
    {
        if ($charcType == 4 && $maxCount == 0) {
            return 'integer';
        } elseif ($charcType == 4 && $maxCount > 0) {
            return 'integer[]';
        } elseif ($charcType == 1 && $maxCount == 1) {
            return 'string';
        } elseif ($charcType == 1 && $maxCount > 1) {
            return 'string[]';
        } elseif ($charcType == 0 && $maxCount == 1) {
            return 'string';
        } elseif ($charcType == 0 && $maxCount == 0) {
            return 'string[]';
        } else {
            return 'string';
        }
    }
}
