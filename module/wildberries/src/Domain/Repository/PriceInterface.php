<?php

declare(strict_types=1);

namespace Wildberries\Domain\Repository;

use Wildberries\Domain\Entity\Price;

interface PriceInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Price|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Price;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Price[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;


    public function getList(): array;

}
