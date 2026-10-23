<?php

namespace Ozon\Domain\Repository\Dwh\Dto\Product\Import;

class ValueDto
{
    public function __construct(
        public int    $dictionary_value_id,
        public string $value,
    ) {
    }

}
