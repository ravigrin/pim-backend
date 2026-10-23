<?php

declare(strict_types=1);

namespace Wildberries\Domain\Repository;

use Wildberries\Domain\Entity\Product;

interface ProductInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Product|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Product;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Product[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;


    /**
     * Возвращает все идентификаторы товаров, объединенные в карточки
     * @return array{array{int:string[]}}
     */
    public function findUnion(): array;


    /**
     * @return Product[]
     */
    public function findToExport(string $seller): array;

    /**
     * @param array $unionIds
     * @return string[]
     */
    public function getProductsCategories(array $unionIds): array;

}
