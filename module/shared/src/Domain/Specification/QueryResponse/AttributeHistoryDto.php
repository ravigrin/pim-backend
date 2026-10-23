<?php

namespace Shared\Domain\Specification\QueryResponse;

use DateTime;

final class AttributeHistoryDto
{
    public function __construct(
        public string            $userId,
        public string|DateTime   $date,
        public string            $attributeId,
        public string|array|null $oldValue,
        public string|array      $newValue
    ) {
    }

    public static function getDto(array $productAttributeHistory): self
    {
        return new self(
            userId: $productAttributeHistory['user_id'],
            date: date("Y-m-d H:i:s", strtotime($productAttributeHistory['created_at'])),
            attributeId: $productAttributeHistory['attribute_id'],
            oldValue: json_decode($productAttributeHistory['old_value'], true),
            newValue: json_decode($productAttributeHistory['new_value'], true)
        );
    }
}
