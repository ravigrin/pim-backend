<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository\Dwh;

use Ozon\Domain\Repository\Dwh\Dto\OzonAttributeDto;
use Ozon\Domain\Repository\Dwh\Dto\OzonCatalogDto;
use Ozon\Domain\Repository\Dwh\Dto\OzonDictionaryDto;
use Ozon\Domain\Repository\Dwh\Dto\Product\Import\ProductDto;

interface OzonImportInterface
{
    /**
     * @return OzonCatalogDto[]
    */
    public function findCatalogsByTreePath(string $treePath): array;

    /**
     * @return OzonAttributeDto[]
     */
    public function findAttributesByTypeId(int $typeId): array;

    /**
     * @return OzonDictionaryDto[]
     */
    public function findDictionary(int $catalogId, int $attributeId): array;

    /**
     * @return ProductDto[]
     */
    public function findProducts(): array;

}
