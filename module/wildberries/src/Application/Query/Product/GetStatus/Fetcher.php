<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product\GetStatus;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Domain\Repository\ProductInterface;

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
        $product = $this->productRepository->findOneBy([
            'productId' => $query->productId
        ]);

        return $product?->getExportStatus();
    }
}
