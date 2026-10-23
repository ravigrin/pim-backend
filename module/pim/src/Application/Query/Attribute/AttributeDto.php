<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute;

use Pim\Domain\Entity\Attribute;

/** @psalm-suppress MissingConstructor */
final class AttributeDto
{
    public string $attributeId;

    public string $name;

    public ?string $alias = null;

    public ?string $groupAlias = null;

    public ?string $description = null;

    public ?string $pimType = null;

    public int $maxCount;

    public bool $isRequired;

    public bool $isReadOnly;

    public static function getDto(Attribute $attribute): self
    {
        $result = new self();
        $result->attributeId = $attribute->getAttributeId();
        $result->name = $attribute->getName();
        $result->alias = $attribute->getAlias();
        $result->groupAlias = $attribute->getAttributeGroupId();
        $result->description = $attribute->getDescription();
        $result->pimType = $attribute->getPimType();
        $result->maxCount = $attribute->getMaxCount();
        $result->isRequired = $attribute->isRequired();
        $result->isReadOnly = $attribute->isReadOnly();

        return $result;
    }
}
