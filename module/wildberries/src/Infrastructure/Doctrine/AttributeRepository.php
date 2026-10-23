<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Repository\AttributeInterface;
use Exception;

/**
 * Репозиторий для работы с атрибутами каталога Wildberries
 */
class AttributeRepository implements AttributeInterface
{
    /** @psalm-var EntityRepository<Attribute> */
    private EntityRepository $repository;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly Connection             $connection,
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Attribute::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Attribute {
        return $this->repository->findOneBy(
            array_merge(
                $criteria,
                ['isDeleted' => false]
            ),
            $orderBy
        );
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Attribute[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy(
            array_merge(
                $criteria,
                ['isDeleted' => false]
            ),
            $orderBy,
            $limit,
            $offset,
        );
    }

    /**
     * @return Attribute[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByCatalog(string $catalogId): array
    {
        $attributes = $this->connection->executeQuery(
            sprintf(
                "select attribute_id from wb_catalog_attribute where catalog_id = '%s'",
                $catalogId
            )
        )->fetchAllAssociative();

        return $this->repository->findBy([
            'attributeId' => array_column($attributes, 'attribute_id'),
            'isDeleted' => false,
        ]);

    }

    /**
     * @inheritDoc
     * @throws \Doctrine\DBAL\Exception
     */
    public function findWithAlias(): array
    {
        $sql = "SELECT attribute_id 
                FROM wb_attribute 
                WHERE alias IS NOT NULL AND is_visible = '%s'";
        $attributes = $this->connection->executeQuery(
            sprintf($sql, true)
        )->fetchAllAssociative();
        return $this->repository->findBy([
            'attributeId' => array_column($attributes, 'attribute_id'),
            'isDeleted' => false,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function findWithEmptyGroup(): array
    {
        $sql = "select attribute_id from wb_attribute where group_id is null";
        $attributes = $this->connection->executeQuery($sql)->fetchAllAssociative();
        return $this->repository->findBy([
            'attributeId' => array_column($attributes, 'attribute_id'),
            'isDeleted' => false,
        ]);
    }

    /**
     * @inheritDoc
     * @throws \Doctrine\DBAL\Exception
     */
    public function findIdsByGroupId(string $attributeGroupId): array
    {
        $sql = "select attribute_id from wb_attribute where group_id = '%s'";
        return $this->connection->executeQuery(sprintf(
            $sql,
            $attributeGroupId
        ))->fetchFirstColumn();
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): array
    {
        $sql = "SELECT attribute_id 
                FROM wb_attribute 
                WHERE source IN ('module', 'product') AND alias NOT IN ('wbUnion', 'media')";
        $attributes = $this->connection->executeQuery(
            $sql
        )->fetchFirstColumn();
        return $this->repository->findBy([
            'attributeId' => $attributes,
            'isDeleted' => false,
        ]);
    }
}
