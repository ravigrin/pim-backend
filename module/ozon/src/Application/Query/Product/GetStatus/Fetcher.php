<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Product\GetStatus;

use Ozon\Domain\Repository\ProductInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductInterface $productRepository,
    ) {
    }

    /**
     * Возвращаем статус отправки на маркетплейс
     *
     * @param Query $query
     * @return int|null
     */
    public function __invoke(Query $query): ?int
    {
        $product = $this->productRepository->findOneByCriteria([
            'productUuid' => $query->productId
        ]);

        return $product?->getExport();
    }
}
