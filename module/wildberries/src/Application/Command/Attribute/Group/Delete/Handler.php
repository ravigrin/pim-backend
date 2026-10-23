<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Attribute\Group\Delete;

use Shared\Domain\Command\CommandHandlerInterface;
use Wildberries\Domain\Entity\AttributeGroup;
use Wildberries\Domain\Repository\AttributeGroupInterface;
use Wildberries\Domain\Repository\AttributeInterface;
use Exception;
use Psr\Log\LoggerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        public AttributeInterface      $attributeRepository,
        public AttributeGroupInterface $attributeGroupRepository,
        public LoggerInterface         $logger
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Command $command): array|true
    {
        $attributeGroup = $this->attributeGroupRepository->findById($command->groupId);

        if (!$attributeGroup instanceof AttributeGroup) {
            $msg = "Группа атрибутов с id: $command->groupId - не существует - удаление не возможно";
            $this->logger->critical(
                message: $msg,
                context: ['source/src/Application/Command/AttributeGroup/Delete/Handler.php::invoke()']
            );
            return [$msg];
        }

        $attributeGroup->setIsDeleted(true);
        $this->attributeGroupRepository->save($attributeGroup);

        $attributeIds = $this->attributeRepository->findIdsByGroupId($attributeGroup->getId());

        foreach ($attributeIds as $id) {
            $attribute = $this->attributeRepository->findById($id);
            $attribute->setAttributeGroup(null);
            $this->attributeRepository->save($attribute);
        }

        return true;
    }
}
