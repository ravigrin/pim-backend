<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ozon\Domain\Entity\Attribute;
use Ozon\Domain\Repository\AttributeInterface;
use Shared\Domain\ValueObject\Uuid;

class AttributeRepository implements AttributeInterface
{
    /** @psalm-var EntityRepository<Attribute> */
    private EntityRepository $repository;

    private Connection $connection;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ManagerRegistry        $doctrine
    ) {
        $this->repository = $this->entityManager->getRepository(Attribute::class);
        /** @var Connection $pim */
        $pim = $this->doctrine->getConnection('pim');
        $this->connection = $pim;
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Attribute[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        $limit = null,
        $offset = null
    ): array {
        $criteria = array_merge($criteria, ["isVisible" => true, "isDeleted" => false]);
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Attribute {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * @return Attribute[]
     * @throws Exception
     */
    public function findByCatalog(?Uuid $catalogId): array
    {
        $attributes = $this->connection->executeQuery(
            "select attribute_uuid from ozon_attribute where catalog_uuid = :catalogUuid or catalog_uuid is null",
            params: ['catalogUuid' => $catalogId?->getValue(), [ParameterType::STRING]],
        )->fetchAllAssociative();

        return $this->repository->findBy([
            'attributeUuid' => array_column($attributes, 'attribute_uuid'),
            'isDeleted' => false,
        ]);
    }

    public function findByCatalogType(int $catalogId, int $typeId, int $attributeId): ?Attribute
    {
        return $this->repository->findOneBy([
            'catalogId' => $catalogId,
            'typeId' => $typeId,
            'attributeId' => $attributeId,
            'isDeleted' => false,
        ]);
    }

}
