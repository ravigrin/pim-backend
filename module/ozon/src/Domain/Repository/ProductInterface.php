<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository;

use Ozon\Domain\Entity\Product;

interface ProductInterface
{
    public function findProductsForFront(): array;

    /**
     * @return array{
     *      productId: string,
     *      name: string,
     *      vendorCode: string,
     *      barcode: string,
     *      price: string,
     *      salePercent: string,
     *      oldPrice: string,
     *      costPrice: string,
     *      productionPrice: string,
     *      productionPriceFlag: bool|null
     *  }[]
     */
    public function findProductsWithPrice(): array;

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Product;

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Product[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array;
}
