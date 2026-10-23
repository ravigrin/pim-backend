<?php

declare(strict_types=1);

namespace Pim\Domain\Repository\Pim;

use Doctrine\DBAL\Exception;
use Pim\Domain\Entity\Product;

interface ProductInterface
{
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

    /**
     * @return array{
     *     productId: string,
     *     vendorCode: string,
     *     productName: string|null,
     *     unitName: string,
     *     brandName: string,
     *     productLineName: string|null,
     *     productStatus: string|null
     * }[]
     * @throws Exception
     */
    public function findProductsForFront(): array;

    public function findByVendorCode(string $sku): ?Product;

    public function getIncrementBy(string $brandId, ?string $productLineId, bool $isKit): int;

    /**
     * Пессимистическая блокировка строки товара (SELECT ... FOR UPDATE).
     * Вызывать внутри транзакции (handler->withTransaction()).
     */
    public function lock(string $productId): void;

    /**
     * @param string[] $productsId
     * @return string[]
     */
    public function findAll(array $productsId): array;
}
