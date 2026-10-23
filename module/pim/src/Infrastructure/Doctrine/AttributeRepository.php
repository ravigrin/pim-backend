<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\Attribute;
use Pim\Domain\Repository\Pim\AttributeInterface;

final readonly class AttributeRepository implements AttributeInterface
{
    /** @psalm-var EntityRepository<Attribute> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Attribute::class);
    }

    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy(
            criteria: $criteria,
            orderBy: $orderBy,
            limit: $limit,
            offset: $offset
        );
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Attribute {
        return $this->repository->findOneBy(
            criteria: $criteria,
            orderBy: $orderBy
        );
    }

    /**
     * @throws Exception
     */
    public function findAttributeGroup(): array
    {
        $connection = $this->entityManager->getConnection();

        $sql = <<<SQL
            select 
                pt.tab_id as tabId, pat.name as tabName, pat.alias as tabAlias, pat.custom_order as tabOrder, 
                pt.group_id as groupId, pag.name as groupName, pag.alias as groupAlias, pag.custom_order as groupOrder, 
                pt.attribute_id as attributeId
            from pim_attribute as pt 
            left join pim_attribute_tab as pat on pt.tab_id = pat.attribute_tab_id
            left join pim_attribute_group as pag on pt.group_id = pag.attribute_group_id
        SQL;

        $attributes = $connection->executeQuery($sql)->fetchAllAssociative();

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
