<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository\Dwh\Dto;

final class OzonAttributeDto
{
    public function __construct(
        public int    $categoryId,
        public int    $typeId,
        public int    $attributeId,
        public int    $attributeComplexId,
        public string $name,
        public string $description,
        public string $type,
        public bool   $isCollection,
        public bool   $isRequired,
        public bool   $isAspect,
        public int    $maxValueCount,
        public string $groupName,
        public int    $groupId,
        public int    $dictionaryId,
    ) {
    }
}
