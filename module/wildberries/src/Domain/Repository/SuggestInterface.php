<?php

declare(strict_types=1);

namespace Wildberries\Domain\Repository;

use Wildberries\Domain\Entity\Suggest;

interface SuggestInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Suggest|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Suggest;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Suggest[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

}
