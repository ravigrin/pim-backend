<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\Catalog;
use Pim\Domain\Repository\Pim\CatalogInterface;

final readonly class CatalogRepository implements CatalogInterface
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

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Catalog[]
     */
    public function findByCriteria(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }
}
