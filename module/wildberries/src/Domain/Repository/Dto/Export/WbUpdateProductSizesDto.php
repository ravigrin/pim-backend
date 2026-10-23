<?php

namespace Wildberries\Domain\Repository\Dto\Export;

use Wildberries\Domain\Repository\Dwh\Dto\WbProductSizesDto;

final class WbUpdateProductSizesDto
{
    /**
     * @param WbProductSizesDto[] $sizes
     */
    public function __construct(
        public array $sizes
    ) {
    }
}
