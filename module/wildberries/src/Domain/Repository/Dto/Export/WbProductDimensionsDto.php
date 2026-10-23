<?php

namespace Wildberries\Domain\Repository\Dto\Export;

final class WbProductDimensionsDto
{
    public function __construct(
        public int $length,
        public int $width,
        public int $height,
    )
    {
    }
}
