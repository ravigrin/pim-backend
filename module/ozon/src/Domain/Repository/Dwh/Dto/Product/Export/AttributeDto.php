<?php

namespace Ozon\Domain\Repository\Dwh\Dto\Product\Export;

final class AttributeDto
{
    /**
     * @param array{dictionary_value_id: int, value: string}[] $values
     */
    public function __construct(
        public int $complex_id,
        public int $id,
        public array $values,
    ) {
    }
}
