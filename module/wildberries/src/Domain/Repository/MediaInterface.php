<?php

declare(strict_types=1);

namespace Wildberries\Domain\Repository;

use Wildberries\Domain\Entity\Media;

interface MediaInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Media|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Media;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Media[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

}
