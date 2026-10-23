<?php

declare(strict_types=1);

namespace Pim\Application\Query\Product\GetStatusByUuid;

use Pim\Domain\Repository\Pim\ProductAttributeInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductAttributeInterface $productAttributeRepository
    ) {
    }

    public function __invoke(Query $query): ?string
    {
        $status = $this->productAttributeRepository->findProductAttribute(
            productId: $query->productId,
            alias: 'status'
        );

        if (is_null($status)) {
            return null;
        }

        return $status->getValue();
    }
}
