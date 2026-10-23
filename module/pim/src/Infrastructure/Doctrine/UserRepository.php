<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\User;
use Pim\Domain\Repository\Pim\UserInterface;

final readonly class UserRepository implements UserInterface
{
    /** @psalm-var EntityRepository<User> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->repository->findBy(['isDeleted' => false]);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->repository->findOneBy([
            'username' => $username,
            'isDeleted' => false,
        ]);
    }

    public function findById(string $userId): ?User
    {
        return $this->repository->findOneBy([
            'userId' => $userId,
            'isDeleted' => false,
        ]);
    }

}
