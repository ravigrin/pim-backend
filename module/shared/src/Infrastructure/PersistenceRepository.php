<?php

namespace Shared\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\Repository\PersistenceInterface;

final readonly class PersistenceRepository implements PersistenceInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function persist(AggregateRoot $entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function save(AggregateRoot $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
