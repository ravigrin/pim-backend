<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ozon\Domain\Entity\AttributeBridge;
use Ozon\Domain\Repository\AttributeBridgeInterface;

class AttributeBridgeRepository implements AttributeBridgeInterface
{
    /** @psalm-var EntityRepository<AttributeBridge> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(AttributeBridge::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?AttributeBridge {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @psalm-param array<string, mixed> $criteria
     * @psalm-param array<string, string>|null $orderBy
     * @return AttributeBridge[]
     */
    public function findByCriteria(
        array $criteria,
        ?array $orderBy,
        int   $limit = null,
        int   $offset = null
    ): array {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

}
