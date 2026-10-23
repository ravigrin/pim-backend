<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\AttributeGroup\GetById;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Application\Query\AttributeGroup\AttributeGroupDto;
use Wildberries\Domain\Entity\AttributeGroup;
use Wildberries\Domain\Repository\AttributeGroupInterface;
use Wildberries\Domain\Repository\AttributeInterface;
use Psr\Log\LoggerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AttributeGroupInterface $attributeGroupRepository,
        private AttributeInterface      $attributeRepository,
        private LoggerInterface         $logger
    ) {
    }

    /**
     * @param Query $query
     * @return AttributeGroupDto|string[]
     */
    public function __invoke(Query $query): AttributeGroupDto|array
    {
        $attributeGroup = $this->attributeGroupRepository->findOneBy(['attributeGroupId' => $query->groupId]);
        if (!$attributeGroup instanceof AttributeGroup) {
            $this->logger->critical(
                message: "AttributeGroup is not AttributeGroup instance",
                context: ['source/src/Application/Query/AttributeGroup/GetById/Fetcher.php::invoke()']
            );
            return ['Not found attribute group by id'];
        }
        $attributes = $this->attributeRepository->findIdsByGroupId($attributeGroup->getAttributeGroupId()) ?? null;
        return AttributeGroupDto::getDto($attributeGroup, $attributes);
    }
}
