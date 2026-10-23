<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute\GetByProductId;

use Pim\Domain\Repository\Pim\ProductAttributeInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductAttributeInterface $productAttributeRepository,
    ) {
    }

    /**
     * @param Query $query
     * @return array<string, string[]|string|null>
     */
    public function __invoke(Query $query): array
    {
        $attributes = $this->productAttributeRepository->findByCriteria(["productId" => $query->productId]);

        $result = [];
        foreach ($attributes as $attribute) {
            $result[$attribute->getAttributeId()] = $attribute->getValue();
        }
        return $result;
    }
}
