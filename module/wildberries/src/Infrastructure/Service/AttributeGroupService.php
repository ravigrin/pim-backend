<?php

namespace Wildberries\Infrastructure\Service;

use Shared\Domain\Command\CommandBusInterface;
use Wildberries\Application\Command\Attribute\Group\Make\Command as AttributeGroupMakeCommand;
use Wildberries\Domain\Repository\AttributeGroupInterface;

/**
 * Сервис для работы с группами атрибутов товаров Wildberries
 */
readonly class AttributeGroupService
{
    private const array GROUPS = [
        'info' => [
            'name' => 'Информация о товаре',
            'type' => 'tab',
            'order' => 1,
            'groups' => [
                'common' => [
                    'name' => 'Общая',
                    'type' => 'group',
                    'order' => 1,
                ]
            ]
        ],
        'category' => [
            'name' => 'Атрибуты категории',
            'type' => 'tab',
            'order' => 2,
            'groups' => [
                'common' => [
                    'name' => 'Общая',
                    'type' => 'group',
                    'order' => 1,
                ]
            ]
        ],
        'dimensions' => [
            'name' => 'Габариты и вес',
            'type' => 'tab',
            'order' => 3,
            'groups' => [
                'box' => [
                    'name' => 'Упаковка',
                    'type' => 'group',
                    'order' => 1,
                ],
                'box-size' => [
                    'name' => 'Размер упаковки',
                    'type' => 'group',
                    'order' => 2,
                ],
                'product-size' => [
                    'name' => 'Размер продукта',
                    'type' => 'group',
                    'order' => 3,
                ],
                'weight' => [
                    'name' => 'Вес',
                    'type' => 'group',
                    'order' => 4,
                ]
            ]
        ],
        'media' => [
            'name' => 'Медиафайлы',
            'type' => 'tab',
            'order' => 4,
            'groups' => [
                'common' => [
                    'name' => 'Общая',
                    'type' => 'group',
                    'order' => 1,
                ]
            ]
        ]
    ];

    public function __construct(
        private CommandBusInterface     $commandBus,
        private AttributeGroupInterface $attributeGroupRepository
    ) {
    }


    /**
     * Наполнение групп
     * @return void
     */
    public function fillGroups(): void
    {

        foreach (self::GROUPS as $alias => $data) {
            $this->commandBus->dispatch(new AttributeGroupMakeCommand(
                name: $data['name'],
                type: $data['type'],
                order: $data['order'],
                alias: $alias
            ));

            $tab = $this->attributeGroupRepository->findOneBy(['alias' => $alias]);
            foreach ($data['groups'] as $group_alias => $group_data) {
                $this->commandBus->dispatch(new AttributeGroupMakeCommand(
                    name: $group_data['name'],
                    type: $group_data['type'],
                    order: $group_data['order'],
                    alias: $group_alias,
                    tabId: $tab->getAttributeGroupId()
                ));
            }
        }
    }


}
