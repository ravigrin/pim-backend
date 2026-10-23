<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\AttributeGroup\GetAll;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Application\Query\AttributeGroup\AttributeGroupDto;
use Wildberries\Application\Query\AttributeGroup\GroupDto;
use Wildberries\Domain\Repository\AttributeGroupInterface;
use Wildberries\Domain\Repository\AttributeInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AttributeInterface      $attributeRepository,
        private AttributeGroupInterface $attributeGroupRepository
    ) {
    }

    /**
     * @return AttributeGroupDto[]
     */
    public function __invoke(Query $query): array
    {
        $response = [];
        $attributeGroupTabs = $this->attributeGroupRepository->findBy(['type' => 'tab']);

        foreach ($attributeGroupTabs as $tab) {
            $groupsDto = [];
            $groups = $this->attributeGroupRepository->findBy(['tabId' => $tab->getAttributeGroupId()]);
            foreach ($groups as $group) {
                $attributes = $this->attributeRepository->findIdsByGroupId($group->getAttributeGroupId());
                if(!$attributes) {
                    continue;
                }
                $groupsDto[] = GroupDto::getDto($group, $attributes);
            }
            if(!$groupsDto) {
                continue;
            }
            $response[] = AttributeGroupDto::getDto($tab, $groupsDto);
        }

        return $response;
    }
}
