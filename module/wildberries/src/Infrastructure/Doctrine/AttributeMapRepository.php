<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Wildberries\Domain\Entity\AttributeMap;
use Wildberries\Domain\Repository\AttributeMapInterface;
use Exception;

/**
 * Репозиторий для работы с мапингом атрибутов PIM к атрибутам Wildberries
 */
class AttributeMapRepository implements AttributeMapInterface
{
    /** @psalm-var EntityRepository<AttributeMap> */
    private EntityRepository $repository;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(AttributeMap::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?AttributeMap {
        return $this->repository->findOneBy(
            $criteria,
            $orderBy
        );
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return AttributeMap[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset,
        );
    }
}
