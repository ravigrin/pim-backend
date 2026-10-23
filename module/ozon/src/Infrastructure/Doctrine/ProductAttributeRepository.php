<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ozon\Domain\Entity\ProductAttribute;
use Ozon\Domain\Repository\ProductAttributeInterface;
use Shared\Domain\ValueObject\Uuid;

class ProductAttributeRepository implements ProductAttributeInterface
{
    /** @psalm-var EntityRepository<ProductAttribute> */
    private EntityRepository $repository;

    private Connection $connection;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(ProductAttribute::class);
        $this->connection = $this->entityManager->getConnection();
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?ProductAttribute {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return ProductAttribute[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @return ProductAttribute[]
     * @throws Exception
     */
    public function findByNoAlias(Uuid $productId, Uuid $catalogId): array
    {
        $sql = <<<SQL
            select attribute_uuid 
            from ozon_attribute 
            where catalog_uuid = :catalogUuid and alias is null 
            group by attribute_uuid
        SQL;

        $attributes = $this->connection->executeQuery(
            sql: $sql,
            params: ['catalogUuid' => $catalogId->getValue()]
        )->fetchAllAssociative();

        return $this->findByCriteria(
            [
                "productUuid" => $productId->getValue(),
                "attributeUuid" => array_column($attributes, 'attribute_uuid')
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function findOneByProductAndAlias(Uuid $productId, string $alias): ?ProductAttribute
    {
        $sql = <<<SQL
            select attribute_uuid
            from ozon_attribute 
            where alias = ?
        SQL;

        $attribute = $this->connection->executeQuery(
            sql: $sql,
            params: [$alias]
        )->fetchFirstColumn();

        $productAttribute = $this->findOneByCriteria([
            "productUuid" => $productId,
            "attributeUuid" => array_shift($attribute)
        ]);

        echo sprintf('alias: %s value: %s', $alias, $productAttribute->getValue()) . PHP_EOL;

        return $productAttribute;
    }

    /**
     * @return ProductAttribute[]
     * @throws Exception
     */
    public function findByExternalId(int $externalId): array
    {
        $sql = <<<SQL
            select attribute_uuid
            from ozon_attribute 
            where attribute_id = ?
        SQL;

        $attribute = $this->connection->executeQuery(
            sql: $sql,
            params: [$externalId]
        )->fetchFirstColumn();

        return $this->findByCriteria([
            "attributeUuid" => array_shift($attribute)
        ]);
    }

}
