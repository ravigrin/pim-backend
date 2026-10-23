<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute\GetUuidByAlias;

use Pim\Domain\Repository\Pim\AttributeInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AttributeInterface $attributeRepository,
    ) {
    }

    public function __invoke(Query $query): ?string
    {
        $attribute = $this->attributeRepository->findOneByCriteria([
            "alias" => $query->alias
        ]);
        return $attribute?->getAttributeId();
    }
}
