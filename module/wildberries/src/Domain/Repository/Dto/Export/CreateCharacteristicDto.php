<?php

namespace Wildberries\Domain\Repository\Dto\Export;

final class CreateCharacteristicDto
{
    /**
     * @param string|string[] $value
     */
    public function __construct(
        public int          $id,
        public string|array $value,
    )
    {
    }
}
