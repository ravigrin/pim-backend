<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\Dictionary;
use Pim\Domain\Repository\Pim\DictionaryInterface;

final readonly class DictionaryRepository implements DictionaryInterface
{
    /** @psalm-var EntityRepository<Dictionary> */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Dictionary::class);
    }

    public function findByCriteria(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Dictionary {
        return $this->repository->findOneBy(
            criteria: $criteria,
            orderBy: $orderBy
        );
    }
}
