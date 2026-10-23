<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository\Dwh\Dto;

final class OzonCatalogDto
{
    public function __construct(
        public string $treePath,
        public int $level,
        public int $typeId,
        public int $parentCatalogId,
        public string $typeName
    ) {
    }
}
