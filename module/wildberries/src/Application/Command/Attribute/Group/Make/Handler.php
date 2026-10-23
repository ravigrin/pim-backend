<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Attribute\Group\Make;

use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Entity\AttributeGroup;
use Wildberries\Domain\Repository\AttributeGroupInterface;
use Wildberries\Domain\Repository\AttributeInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private EntityStoreService      $entityStoreService,
        private AttributeInterface      $attributeRepository,
        private AttributeGroupInterface $attributeGroupRepository,
        private LoggerInterface         $logger
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        // Если не передан алиас - формируем его из имени
        if (is_null($command->alias) || preg_match('/[А-Яа-яЁё]/u', $command->alias)) {
            $command->alias = $this->makeAlias($command->name);
        }

        $params = ['alias' => $command->alias];
        if ($command->tabId) {
            $params['tabId'] = [$command->tabId];
        }
        if (!$command->order) {
            $command->order = 1;
        }

        // Если не передан groupId - создаем новую группу
        if (!$command->groupId) {
            $attributeGroup = $this->attributeGroupRepository->findOneBy($params);

            if ($attributeGroup) {
                $msg = "Группа атрибутов с алиасом $command->alias и tabId: $command->tabId - уже существует 
                        - новая не создана";
                $this->logger->critical(message: $msg);
            }

            $attributeGroup = new AttributeGroup(
                attributeGroupId: Uuid::uuid7()->toString(),
                name: $command->name,
                alias: $command->alias,
                type: $command->type,
                orderGroup: $command->order,
                tabId: $command->tabId ?? null
            );

        } else {
            $attributeGroup = $this->attributeGroupRepository->findOneBy(['attributeGroupId' => $command->groupId]);
            $attributeGroup->setName($command->name);
            $attributeGroup->setAlias($command->alias);
            $attributeGroup->setType($command->type);
            $attributeGroup->setOrderGroup($command->order);
            if($command->tabId) {
                $attributeGroup->setTabId($command->tabId);
            }
        }
        // Сохраняем группу
        $this->entityStoreService->commit($attributeGroup);

        if (is_null($command->attributes)) {
            return;
        }

        // Если переданы id атрибутов, присваиваем им группы
        foreach ($command->attributes as $attributeId) {
            $attribute = $this->attributeRepository->findOneBy(["attributeId" => $attributeId]);
            if (!$attribute instanceof Attribute) {
                throw new Exception(
                    message: "Attribute with id: $attributeId is not Attribute instance 
                    - Wildberries/Application/Command/Attribute/Group/Make/Handler.php::invoke()",
                    code: 500
                );
            }

            $attribute->setGroupId($attributeGroup->getAttributeGroupId());
            $this->entityStoreService->commit($attribute);
        }
    }

    /**
     * @param string $name
     * @return string
     */
    private function makeAlias(string $name): string
    {
        $cyr = [' ', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т',
            'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У',
            'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'];
        $lat = ['_', 'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U',
            'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'];

        return str_replace($cyr, $lat, $name);
    }
}
