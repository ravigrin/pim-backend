<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute\GetValueByProductAlias;

use Pim\Domain\Repository\Pim\ProductAttributeInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductAttributeInterface $productAttributeRepository
    ) {
    }

    /**
     * @param Query $query
     * @return string|string[]|null
     */
    public function __invoke(Query $query): null|string|array
    {
        $productAttribute = $this->productAttributeRepository->findProductAttribute($query->productId, $query->alias);

        return $productAttribute->getValue();
    }
}
