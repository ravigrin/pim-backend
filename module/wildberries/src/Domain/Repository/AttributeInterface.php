<?php

declare(strict_types=1);

namespace Wildberries\Domain\Repository;

use Wildberries\Domain\Entity\Attribute;

interface AttributeInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Attribute|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Attribute;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Attribute[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;


    /**
     * @param string $catalogId
     * @return Attribute[]
     */
    public function findByCatalog(string $catalogId): array;


    /**
     * @return Attribute[]
     */
    public function findWithAlias(): array;

    /**
     * Возвращает атрибуты группы Инфо
     * @return Attribute[]
     */
    public function getInfo(): array;

    /**
     * @return Attribute[]
     */
    public function findWithEmptyGroup(): array;

    /**
     * @param string $attributeGroupId
     * @return string[]
     */
    public function findIdsByGroupId(string $attributeGroupId): array;
}
