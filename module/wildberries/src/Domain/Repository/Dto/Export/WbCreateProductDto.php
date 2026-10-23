<?php

namespace Wildberries\Domain\Repository\Dto\Export;

final class WbCreateProductDto
{
    /**
     * @param int $subjectId
     * @param WbCreateProductItemDto[] $variants
     */
    public function __construct(
        public int   $subjectId,
        public array $variants
    )
    {
    }
}
