<?php

declare(strict_types=1);

namespace Pim\Domain\Repository\Pim;

use Pim\Domain\Entity\AttributeTab;

interface AttributeTabInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?AttributeTab;

}
