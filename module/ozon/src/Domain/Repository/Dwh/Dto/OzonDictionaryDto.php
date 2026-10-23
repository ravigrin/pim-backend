<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository\Dwh\Dto;

final class OzonDictionaryDto
{
    public function __construct(
        public int $categoryId,
        public int $attributeId,
        public int $dictionaryId,
        public string $value,
        public string $info,
        public string $picture,
    ) {
    }
}
