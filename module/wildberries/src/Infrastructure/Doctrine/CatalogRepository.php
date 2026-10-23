<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Shared\Domain\ValueObject\Uuid;
use Wildberries\Domain\Entity\Catalog;
use Wildberries\Domain\Repository\CatalogInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final readonly class CatalogRepository implements CatalogInterface
{
    private ?string $table;

    /** @psalm-var EntityRepository<Catalog> */
    private EntityRepository $repository;

    /**
     * @throws \Exception
     */
    public function __construct(
        private Connection             $connection,
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface  $parameterBag
    ) {
        if (!$this->table = $this->parameterBag->get('wildberries')['tables']['catalog']) {
            throw new \Exception(
                'App\Wildberries\Infrastructure\Doctrine\CatalogRepository::construct() - FAIL LOAD TABLE'
            );
        }

        $this->repository = $this->entityManager->getRepository(Catalog::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Catalog {
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
     * @return Catalog[]
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
     * @inheritDoc
     * @throws Exception
     */
    public function findAll(bool $isActive = true, bool $isDeleted = false): array
    {
        $sql = "SELECT * FROM $this->table 
                WHERE is_active = :isActive 
                AND is_deleted = :isDeleted";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('isActive', $isActive);
        $stmt->bindValue('isDeleted', $isDeleted);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function findById(Uuid $catalogId, bool $isDeleted = false): array
    {
        $sql = "SELECT * FROM $this->table 
                WHERE catalog_id = :catalogId 
                AND is_deleted = :isDeleted";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('catalogId', $catalogId);
        $stmt->bindValue('isDeleted', $isDeleted);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function findByObjectId(int $objectId, bool $isDeleted = false): array
    {
        $sql = "SELECT * FROM $this->table 
                WHERE object_id = :objectId 
                AND is_deleted = :isDeleted";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('objectId', $objectId);
        $stmt->bindValue('isDeleted', $isDeleted);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function findByName(string $name, bool $isDeleted = false): array
    {
        $sql = "SELECT * FROM $this->table 
                WHERE name = :name 
                AND is_deleted = :isDeleted";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('name', $name);
        $stmt->bindValue('isDeleted', $isDeleted);

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
