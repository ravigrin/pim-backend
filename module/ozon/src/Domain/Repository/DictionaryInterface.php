<?php

declare(strict_types=1);

namespace Ozon\Domain\Repository;

use Ozon\Domain\Entity\Dictionary;
use Shared\Domain\ValueObject\Uuid;

interface DictionaryInterface
{
    public function findByExternalId(int $externalId): ?Dictionary;

    /**
     * @return Dictionary[]
     */
    public function findByAttribute(
        int $attributeId
    ): array;

    /**
     * @return Dictionary[]
     */
    public function findByAttributeAndCatalog(
        string $catalogId,
        string $attributeId,
    ): array;

    /**
     * Поиск по Id атрибута, Id каталога и значению
     */
    public function findByCatalogAttributeValue(
        Uuid   $attributeId,
        Uuid   $catalogId,
        string $value
    ): ?Dictionary;

    public function findByExternalCatalogValue(
        int    $catalogId,
        string $value,
    ): ?Dictionary;

    /**
     * @return Dictionary[]
     */
    public function findByExternalCatalogAttribute(
        int $catalogId,
        int $attributeId,
    ): array;

    public function findByExternalCatalogAttributeDictionary(
        int $catalogId,
        int $attributeId,
        int $dictionaryId
    ): ?Dictionary;

    /**
     * @return Dictionary[]
     */
    public function findByAttributeValue(int $attributeId, string $value): array;
}
