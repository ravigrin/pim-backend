<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository;

use Ozon\Domain\Entity\ProductAttribute;
use Shared\Domain\ValueObject\Uuid;

interface ProductAttributeInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?ProductAttribute;

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return ProductAttribute[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array;

    public function findOneByProductAndAlias(Uuid $productId, string $alias): ?ProductAttribute;

    public function findByNoAlias(Uuid $productId, Uuid $catalogId): array;

    /**
     * @return ProductAttribute[]
     */
    public function findByExternalId(int $externalId): array;

}
