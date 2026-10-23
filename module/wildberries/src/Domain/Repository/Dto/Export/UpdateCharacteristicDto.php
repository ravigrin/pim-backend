<?php

namespace Wildberries\Domain\Repository\Dto\Export;

final class UpdateCharacteristicDto
{
    /**
     * @param string|string[] $value
     */
    public function __construct(
        public int          $id,
        public string       $name,
        public string|array $value,
    )
    {
    }
}
