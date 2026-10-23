<?php

namespace Wildberries\Domain\Repository\Dwh\Dto;

final class WbProductAttributeDto
{
    public function __construct(
        public string  $sellerName,
        public int     $nmId,
        public int     $imtId,
        public string  $vendorCode,
        public string  $brand,
        public string  $subjectName,
        public int     $subjectId,
        public string  $title,
        public string  $nmUuid,
        public ?string $description = null,
        public ?string $wbUpdateAt = null,
        public ?array  $tags = null,
    )
    {
    }
}
