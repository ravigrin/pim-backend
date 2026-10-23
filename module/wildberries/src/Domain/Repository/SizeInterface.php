<?php

declare(strict_types=1);

namespace Wildberries\Domain\Repository;

use Wildberries\Domain\Entity\Size;

interface SizeInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Size|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Size;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Size[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

}
