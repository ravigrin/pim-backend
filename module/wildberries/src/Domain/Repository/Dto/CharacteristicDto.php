<?php

namespace Wildberries\Domain\Repository\Dto;

final class CharacteristicDto
{
    /**
     * @param string|string[] $value
     */
    public function __construct(
        public string       $name,
        public string|array $value,
    ) {
    }
}
