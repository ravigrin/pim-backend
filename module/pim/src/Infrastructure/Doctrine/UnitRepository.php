<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\Unit;
use Pim\Domain\Repository\Pim\UnitInterface;

final readonly class UnitRepository implements UnitInterface
{
    /** @psalm-var EntityRepository<Unit> */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Unit::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Unit {
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
     * @return Unit[]
     */
    public function findByCriteria(
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

    public function findUuidName(): array
    {
        $units = $this->findByCriteria([]);
        $result = [];
        foreach ($units as $unit) {
            $result[$unit->getId()] = $unit->getName();
        }
        return $result;
    }

}
