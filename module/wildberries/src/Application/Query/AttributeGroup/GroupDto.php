<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\AttributeGroup;

use Wildberries\Domain\Entity\AttributeGroup;

/** @psalm-suppress MissingConstructor */
final class GroupDto
{
    /**
     * @param string $groupId
     * @param string $name
     * @param string $alias
     * @param int $order
     * @param null|string[] $attributes
     */
    public function __construct(
        public string $groupId,
        public string $name,
        public string $alias,
        public int    $order,
        public ?array $attributes
    ) {
    }

    /**
     * @param AttributeGroup $attributeGroup
     * @param string[]|null $attributes
     * @return self
     */
    public static function getDto(AttributeGroup $attributeGroup, ?array $attributes = null): self
    {
        return new self(
            groupId: $attributeGroup->getAttributeGroupId(),
            name: $attributeGroup->getName(),
            alias: $attributeGroup->getAlias(),
            order: $attributeGroup->getOrderGroup(),
            attributes: $attributes ?? null
        );
    }
}
