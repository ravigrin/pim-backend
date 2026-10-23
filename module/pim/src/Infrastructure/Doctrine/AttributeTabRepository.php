<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\AttributeTab;
use Pim\Domain\Repository\Pim\AttributeTabInterface;

final readonly class AttributeTabRepository implements AttributeTabInterface
{
    /** @psalm-var EntityRepository<AttributeTab> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(AttributeTab::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?AttributeTab {
        return $this->repository->findOneBy(
            criteria: $criteria,
            orderBy: $orderBy
        );
    }
}
