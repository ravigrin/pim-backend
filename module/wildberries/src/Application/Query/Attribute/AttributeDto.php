<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Attribute;

use Wildberries\Domain\Entity\Attribute;

/** @psalm-suppress MissingConstructor */
final class AttributeDto
{
    public string $attributeId;

    public string $name;

    public ?string $alias = null;

    public ?string $groupId = null;

    public ?string $description = null;
    public ?string $marketplaceType = null;

    public int $maxCount;

    public bool $isRequired;

    public bool $isReadOnly;

    public bool $isDictionary = false;
    public static function getDto(Attribute $attribute): self
    {
        $result = new self();
        $result->attributeId = $attribute->getAttributeId();
        $result->name = $attribute->getName();
        $result->alias = $attribute->getAlias();
        $result->groupId = $attribute->getGroupId();
        $result->description = $attribute->getDescription();
        $result->marketplaceType = $attribute->getType();
        $result->maxCount = $attribute->getMaxCount();
        $result->isRequired = $attribute->isRequired();
        $result->isReadOnly = $attribute->isReadOnly();
        $result->isDictionary = $attribute->isDictionary();
        return $result;
    }
}
