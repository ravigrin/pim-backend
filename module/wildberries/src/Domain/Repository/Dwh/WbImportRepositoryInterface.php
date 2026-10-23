<?php

namespace Wildberries\Domain\Repository\Dwh;

use Wildberries\Application\Command\Price\Import\PriceDto;
use Wildberries\Application\Query\Suggest\SuggestDto;
use Wildberries\Domain\Repository\Dto\WbAttributeDto;
use Wildberries\Domain\Repository\Dto\WbCatalogDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductDto;

interface WbImportRepositoryInterface
{
    public function getColorDictionary(): SuggestDto;

    /**
     * Проучение словарей полов
     * @return SuggestDto[]
     */
    public function getKindDictionary(): array;

    /**
     * @return SuggestDto[]
     */
    public function getCountriesDictionary(): array;

    /**
     * @return SuggestDto[]
     */
    public function getSeasonsDictionary(): array;


    public function getTnvedDictionary(): SuggestDto;

    /**
     * @param int[] $parentsId
     * @return WbCatalogDto[]
     */
    public function findCatalogsByParents(array $parentsId): array;

    /**
     * @return WbAttributeDto[]
     */
    public function findAttributesByCatalog(int $externalId): array;

    /**
     * @return WbProductDto[]
     */
    public function getAllProducts(): array;

    /**
     * @return PriceDto[]
     */
    public function getPrice(): array;

    /**
     * @param string $article
     * @param int $finalPrice
     * @return array
     */
    public function getNetCost(string $article, int $finalPrice): array;
}
