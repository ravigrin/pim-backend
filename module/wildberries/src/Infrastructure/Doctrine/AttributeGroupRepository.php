<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Wildberries\Domain\Entity\AttributeGroup;
use Wildberries\Domain\Repository\AttributeGroupInterface;

final readonly class AttributeGroupRepository implements AttributeGroupInterface
{
    /** @psalm-var EntityRepository<AttributeGroup> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(AttributeGroup::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?AttributeGroup {
        return $this->repository->findOneBy(
            array_merge(
                $criteria,
                ['isDeleted' => false]
            ),
            $orderBy
        );
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return AttributeGroup[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
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
}
