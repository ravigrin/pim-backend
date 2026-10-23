<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Attribute;

use Ozon\Domain\Entity\Attribute;

/**
 * Уверсальная DTO для резульнат работы
 *
 * @psalm-suppress MissingConstructor
 */
final class AttributeDto
{
    public string $attributeId;

    public string $name;

    public ?string $alias = null;

    public ?string $description = null;

    public ?string $marketplaceType = null;

    public ?string $pimType = null;

    public int $maxCount;

    public bool $isRequired;

    public bool $isReadOnly;

    public bool $isAspect;

    public bool $isCollection;

    public bool $isDictionary;

    public ?string $defaultValue = null;

    public ?string $unionFlag = null;

    public static function getDto(Attribute $attribute): self
    {
        $result = new self();
        $result->attributeId = (string)$attribute->getAttributeUuid();
        $result->name = $attribute->getName();
        $result->alias = $attribute->getAlias();
        $result->description = $attribute->getDescription();
        $result->marketplaceType = $attribute->getType();
        $result->pimType = $attribute->getPimType();
        $result->maxCount = $attribute->getMaxValueCount();
        $result->isRequired = $attribute->isRequired();
        $result->isReadOnly = $attribute->isReadOnly();
        $result->isCollection = $attribute->isCollection();
        $result->isAspect = $attribute->isAspect();
        $result->isDictionary = (bool)$attribute->getDictionaryId();

        return $result;
    }
}
