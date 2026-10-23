<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Attribute\Group\Init;

use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Repository\AttributeGroupInterface;
use Wildberries\Domain\Repository\AttributeInterface;
use Exception;
use Psr\Log\LoggerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private AttributeInterface      $attributeRepository,
        private AttributeGroupInterface $attributeGroupRepository,
        private PersistenceInterface    $persistenceRepository,
        private LoggerInterface         $logger
    )
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        $dimensions = [
            'product-size' => [
                'Ширина предмета',
                'Глубина предмета',
                'Высота предмета'
            ],
            'box-size' => [
                'Ширина упаковки, см',
                'Длина упаковки, см',
                'Высота упаковки, см'
            ],
            'box' => [
                'Форма упаковки',
                'Упаковка'
            ],
            'weight' => [
                'Вес товара без упаковки (г)',
                'Вес товара с упаковкой (г)',
                'Вес с упаковкой (кг)',
                'Вес без упаковки (кг)'
            ]
        ];

        $attributeGroupTabs = $this->attributeGroupRepository->findBy(['type' => 'tab']);

        foreach ($attributeGroupTabs as $tab) {
            switch ($tab->getAlias()) {
                case 'info':
                    $group = $this->attributeGroupRepository
                        ->findOneBy(['alias' => 'common', 'tabId' => $tab->getAttributeGroupId()]);
                    if (!$group) {
                        break;
                    }
                    $infoAttributes = $this->attributeRepository->getInfo();

                    if (empty($infoAttributes)) {
                        $this->logger->warning(
                            message: 'Not set Info attributes / attributes with alias
                            - source/src/Application/Command/AttributeGroup/Init/Handler.php::__invoke()'
                        );
                        break;
                    }
                    foreach ($infoAttributes as $infoAttribute) {
                        $infoAttribute->setGroupId($group->getAttributeGroupId());
                        $this->persistenceRepository->persist($infoAttribute);
                    }
                    break;
                case 'dimensions':
                    foreach ($dimensions as $groupAlias => $names) {
                        $group = $this->attributeGroupRepository
                            ->findOneBy(['alias' => $groupAlias, 'tabId' => $tab->getAttributeGroupId()]);
                        if (!$group) {
                            break;
                        }
                        foreach ($names as $name) {
                            $dimensionAttribute = $this->attributeRepository->findOneBy(["name" => $name]);
                            if (!$dimensionAttribute instanceof Attribute) {
                                $this->logger->warning(
                                    message: 'Dimension attribute is not instance of Attribute
                                    - source/src/Application/Command/AttributeGroup/Init/Handler.php::__invoke()'
                                );
                                break;
                            }
                            $dimensionAttribute->setGroupId($group->getAttributeGroupId());
                            $this->persistenceRepository->persist($dimensionAttribute);
                        }
                    }
                    break;
                case 'category':
                    if (!$group = $this->attributeGroupRepository
                        ->findOneBy(['alias' => 'common', 'tabId' => $tab->getAttributeGroupId()])) {
                        break;
                    }

                    if (!$categoryAttributes = $this->attributeRepository->findBy(['source' => 'characteristic'])) {
                        $this->logger->warning(
                            message: 'Not set category attributes / attributes with source "characteristic"
                            - source/src/Application/Command/AttributeGroup/Init/Handler.php::__invoke()'
                        );
                        break;
                    }
                    foreach ($categoryAttributes as $categoryAttribute) {
                        $categoryAttribute->setGroupId($group->getAttributeGroupId());
                        $this->persistenceRepository->persist($categoryAttribute);
                    }
                    break;
                case 'media':
                    $group = $this->attributeGroupRepository
                        ->findOneBy(['alias' => 'common', 'tabId' => $tab->getAttributeGroupId()]);
                    if (!$group) {
                        break;
                    }
                    $mediaAttribute = $this->attributeRepository->findOneBy(["alias" => 'media']);

                    if (!$mediaAttribute instanceof Attribute) {
                        $this->logger->warning(
                            message: 'Media attribute is not instance of Attribute 
                            - source/src/Application/Command/AttributeGroup/Init/Handler.php::__invoke()'
                        );
                        break;
                    }
                    $mediaAttribute->setGroupId($group->getAttributeGroupId());
                    $this->persistenceRepository->persist($mediaAttribute);
                    break;
            }
        }

        $this->persistenceRepository->flush();
    }

}
