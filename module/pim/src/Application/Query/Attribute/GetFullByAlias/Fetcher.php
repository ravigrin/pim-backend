<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute\GetFullByAlias;

use Pim\Domain\Entity\Attribute;
use Pim\Domain\Repository\Pim\AttributeInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AttributeInterface $attributeRepository,
    ) {
    }

    /**
     * @param Query $query
     * @return Attribute|null
     */
    public function __invoke(Query $query): ?Attribute
    {
        return $this->attributeRepository->findOneByCriteria(['alias' => $query->alias]);
    }
}
