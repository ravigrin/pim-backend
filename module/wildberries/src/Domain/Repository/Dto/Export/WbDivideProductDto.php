<?php

namespace Wildberries\Domain\Repository\Dto\Export;

final class WbDivideProductDto
{
    /**
     * @param int[] $nmIDs
     */
    public function __construct(
        public array $nmIDs
    ) {
    }
}
