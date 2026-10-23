<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\AttributeGroup;
use Pim\Domain\Repository\Pim\AttributeGroupInterface;

final readonly class AttributeGroupRepository implements AttributeGroupInterface
{
    /** @psalm-var EntityRepository<AttributeGroup> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(AttributeGroup::class);
    }

    public function findById(int $attributeGroupId): ?AttributeGroup
    {
        return $this->repository->findOneBy([
            'attributeGroupId' => $attributeGroupId,
            'isDeleted' => false,
        ]);
    }

    public function findByAlias(string $alias): ?AttributeGroup
    {
        return $this->repository->findOneBy([
            'alias' => $alias,
            'isDeleted' => false,
        ]);
    }

    /**
     * @return AttributeGroup[]
     */
    public function getAll(): array
    {
        return $this->repository->findBy([
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
    ): ?AttributeGroup {
        return $this->repository->findOneBy(
            criteria: $criteria,
            orderBy: $orderBy
        );
    }
}
