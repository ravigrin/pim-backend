<?php

namespace Wildberries\Domain\Repository\Dwh\Dto;

final class WbProductDimensionsDto
{
    public function __construct(
        public ?string $length = null,
        public ?string $width = null,
        public ?string $height = null,
    )
    {
    }
}
