<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute\GetByAliases;

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
     * @return array<string, string>
     */
    public function __invoke(Query $query): array
    {
        $attributes = $this->attributeRepository->findByCriteria(['alias' => $query->aliases]);

        $result = [];
        foreach ($attributes as $attribute) {
            $result[$attribute->getAlias()] = $attribute->getAttributeId();
        }

        return $result;
    }
}
