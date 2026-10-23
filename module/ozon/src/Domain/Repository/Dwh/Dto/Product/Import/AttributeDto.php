<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository\Dwh\Dto\Product\Import;

final class AttributeDto
{
    /**
     * @param int $attribute_id
     * @param int $complex_id
     * @param ValueDto[] $values
     */
    public function __construct(
        public int   $attribute_id,
        public int   $complex_id,
        public array $values,
    ) {
    }
}
