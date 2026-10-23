<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\AttributeGroup;

use Wildberries\Domain\Entity\AttributeGroup;

/** @psalm-suppress MissingConstructor */
final class AttributeGroupDto
{
    /**
     * @param string $tabId
     * @param string $name
     * @param string $alias
     * @param int $order
     * @param GroupDto[]|null $groups
     */
    public function __construct(
        public string $tabId,
        public string $name,
        public string $alias,
        public int    $order,
        public ?array $groups = null
    ) {
    }

    /**
     * @param AttributeGroup $attributeGroup
     * @param GroupDto[]|null $groups
     * @return self
     */
    public static function getDto(AttributeGroup $attributeGroup, ?array $groups = null): self
    {
        return new self(
            tabId: $attributeGroup->getAttributeGroupId(),
            name: $attributeGroup->getName(),
            alias: $attributeGroup->getAlias(),
            order: $attributeGroup->getOrderGroup(),
            groups: $groups
        );
    }
}
