<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ozon\Domain\Entity\Catalog;
use Ozon\Domain\Repository\CatalogInterface;

class CatalogRepository implements CatalogInterface
{
    /** @psalm-var EntityRepository<Catalog> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Catalog::class);
    }

    /**
     * @return Catalog[]
     */
    public function findAll(): array
    {
        return $this->repository->findBy([
            'isDeleted' => false,
        ]);
    }

    public function findById(string $catalogId): ?Catalog
    {
        return $this->repository->findOneBy([
            'catalogId' => $catalogId,
            'isDeleted' => false,
        ]);
    }

    public function findOneByExternalId(int $externalId): ?Catalog
    {
        return $this->repository->findOneBy([
            'catalogId' => $externalId,
            'isDeleted' => false,
        ]);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Catalog {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

}
