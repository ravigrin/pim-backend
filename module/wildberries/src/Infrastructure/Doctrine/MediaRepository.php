<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Wildberries\Domain\Entity\Media;
use Wildberries\Domain\Repository\MediaInterface;


/**
 * Репозиторий для работы с медиа файлами товаров Wildberries
 */
class MediaRepository implements MediaInterface
{
    /** @psalm-var EntityRepository<Media> */
    private EntityRepository $repository;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        $this->repository = $this->entityManager->getRepository(Media::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Media|null
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Media
    {
        return $this->repository->findOneBy(
            $criteria,
            $orderBy
        );
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Media[]
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
