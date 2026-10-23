<?php

declare(strict_types=1);

namespace Wildberries\Domain\Repository;

use Wildberries\Application\Query\Product\GetCodeAndLinkByUuid\ProductCodeAndLinkDto;
use Wildberries\Domain\Entity\ProductAttribute;
use Wildberries\Domain\Repository\Dto\CharacteristicDto;

interface ProductAttributeInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return ProductAttribute|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?ProductAttribute;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return ProductAttribute[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param string $productId
     * @return CharacteristicDto[]
     */
    public function getCharacteristics(string $productId): array;


    /**
     * Возвращает vendorCode и publicProductLink (для модуля ad-analytics)
     * @param string $productId
     * @return ProductCodeAndLinkDto|null
     */
    public function getCodeAndLink(string $productId): ?ProductCodeAndLinkDto;

}
