<?php

namespace Wildberries\Domain\Repository\Dto\Export;

final class WbUnionProductDto
{
    /**
     * @param int[] $nmIDs
     */
    public function __construct(
        public int   $targetIMT,
        public array $nmIDs
    ) {
    }
}
