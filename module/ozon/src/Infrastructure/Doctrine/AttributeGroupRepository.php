<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ozon\Domain\Entity\AttributeGroup;
use Ozon\Domain\Repository\AttributeGroupInterface;
use Shared\Domain\ValueObject\Uuid;

class AttributeGroupRepository implements AttributeGroupInterface
{
    /** @psalm-var EntityRepository<AttributeGroup> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(AttributeGroup::class);
    }

    public function findById(int $attributeGroupId): ?AttributeGroup
    {
        return $this->repository->findOneBy([
            'attributeGroupId' => $attributeGroupId,
            'isDeleted' => false,
        ]);
    }

    public function findByAlias(string $alias): ?AttributeGroup
    {
        return $this->repository->findOneBy([
            'alias' => $alias,
            'isDeleted' => false,
        ]);
    }

    /**
     * @return AttributeGroup[]
     */
    public function getAll(): array
    {
        return $this->repository->findBy([
            'isDeleted' => false,
        ]);
    }

    /**
     * @throws Exception
     */
    public function findAttributeGroup(Uuid $catalogId): array
    {
        $connection = $this->entityManager->getConnection();

        $sql = <<<SQL
            select 
                pt.tab_uuid as tabId, 
                pat.name as tabName, 
                pat.alias as tabAlias, 
                pat.custom_order as tabOrder, 
                pt.group_uuid as groupId, 
                pag.name as groupName, 
                pag.alias as groupAlias, 
                pag.custom_order as groupOrder, 
                pt.attribute_uuid as attributeId
            from ozon_attribute as pt 
            left join ozon_attribute_tab as pat on pt.tab_uuid = pat.attribute_tab_uuid
            left join ozon_attribute_group as pag on pt.group_uuid = pag.attribute_group_uuid
            where pt.catalog_uuid = ? or pt.catalog_uuid is null
        SQL;

        $attributes = $connection->executeQuery(
            $sql,
            [$catalogId]
        )->fetchAllAssociative();

        // пример для сборки массива
        //{
        //  "tabId": "",
        //  "name": "Информация о товаре",
        //  "alias": "info",
        //  "order": 1
        //  "groups": [
        //    {
        //      "groupId": "",
        //      "name": "Все",
        //      "alias": "all",
        //      "order": 1
        //      "attributes": [
        //        "",
        //        ...
        //      ]
        //    },
        //  ],
        //}

        $result = [];
        foreach ($attributes as $attribute) {

            $tabId = $attribute['tabId'];
            $groupId = $attribute['groupId'];

            $result[$tabId]['tabId'] = $attribute['tabId'];
            $result[$tabId]['name'] = $attribute['tabName'];
            $result[$tabId]['alias'] = $attribute['tabAlias'];
            $result[$tabId]['order'] = (int)$attribute['tabOrder'];
            $result[$tabId]['groups'][$groupId]['groupId'] = $attribute['groupId'];
            $result[$tabId]['groups'][$groupId]['name'] = $attribute['groupName'];
            $result[$tabId]['groups'][$groupId]['alias'] = $attribute['groupAlias'];
            $result[$tabId]['groups'][$groupId]['order'] = (int)$attribute['groupOrder'];
            $result[$tabId]['groups'][$groupId]['attributes'][] = $attribute['attributeId'];
        }

        // чистим ключи $tabId и $groupId
        $result = array_values($result);
        foreach ($result as $key => $item) {
            $result[$key]['groups'] = array_values($item['groups']);
        }

        return $result;
    }


}
