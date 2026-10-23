<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Wildberries\Domain\Entity\Size;
use Wildberries\Domain\Repository\SizeInterface;

/**
 * Репозиторий для работы с размерами товаров Wildberries
 */
class SizeRepository implements SizeInterface
{
    /** @psalm-var EntityRepository<Size> */
    private EntityRepository $repository;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        $this->repository = $this->entityManager->getRepository(Size::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Size|null
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Size
    {
        return $this->repository->findOneBy(
            $criteria,
            $orderBy
        );
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Size[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array
    {
        return $this->repository->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset,
        );
    }
}
