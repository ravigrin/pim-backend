<?php

namespace Wildberries\Infrastructure\Dwh;

use JsonException;
use Wildberries\Application\Command\Price\Import\PriceDto;
use Wildberries\Application\Query\Suggest\SuggestDto;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Wildberries\Domain\Repository\Dto\WbAttributeDto;
use Wildberries\Domain\Repository\Dto\WbCatalogDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductAttributeDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductCharacteristicsDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductDimensionsDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductMediaDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductSizesDto;
use Wildberries\Domain\Repository\Dwh\WbImportRepositoryInterface;

final readonly class WbImportRepository implements WbImportRepositoryInterface
{
    /**
     * @var Connection Соединение с DWH
     */
    private Connection $connection;

    public function __construct(
        private ManagerRegistry $doctrine
    )
    {
        /** @var Connection $dwh */
        $dwh = $this->doctrine->getConnection('dwh');
        $this->connection = $dwh;
    }

    /**
     * Возвращает каталог по идентификатору родителького каталога
     * @throws Exception
     */
    public function findCatalogsByParents(array $parentsId): array
    {
        /**
         * @var array{
         *       category_id: string,
         *       parent_category_id: string,
         *       category_name: string,
         *       parent_category_name: string,
         *       is_visible: string
         *   }[] $catalogs
         */
        $catalogs = $this->connection->executeQuery(
            sql: "select * from wb.categories where parent_category_id in (?)",
            params: $parentsId
        )->fetchAllAssociative();

        return array_map(static fn(array $catalog): WbCatalogDto => new WbCatalogDto(
            categoryId: (int)$catalog['category_id'],
            parentCategoryId: (int)$catalog['parent_category_id'],
            categoryName: $catalog['category_name'],
            parentCategoryName: $catalog['parent_category_name'],
            isVisible: (bool)$catalog['is_visible'],
        ), $catalogs);
    }

    /**
     * Возвращает список аттрибутов по идентификатору каталога
     * @throws Exception
     */
    public function findAttributesByCatalog(int $externalId): array
    {
        /**
         * @var array{
         *       category_id: string,
         *       parent_category_id: string,
         *       is_visible: string,
         *       popular: string,
         *       is_required: string,
         *       unit_name: string,
         *       charc_type: string,
         *       max_count: string,
         *       characteristic_name: string
         *   }[] $catalogs
         */
        $catalogs = $this->connection->executeQuery(
            sql: "select * from wb.characteristics where category_id = ?",
            params: [$externalId]
        )->fetchAllAssociative();

        return array_map(static fn(array $catalog): WbAttributeDto => new WbAttributeDto(
            categoryId: (int)$catalog['category_id'],
            parentCategoryId: (int)$catalog['parent_category_id'],
            characteristicName: $catalog['characteristic_name'],
            isRequired: (bool)$catalog['is_required'],
            unitName: $catalog['unit_name'],
            maxCount: (int)$catalog['max_count'],
            popular: (bool)$catalog['popular'],
            charcType: (int)$catalog['charc_type']
        ), $catalogs);
    }

    /**
     * Возвращает список товаров
     * @return WbProductDto[]
     * @throws Exception
     * @throws JsonException
     */
    public function getAllProducts(): array
    {

        /**
         * @var array{
         *       seller_name: string,
         *       imtID: string,
         *       nmID: string,
         *       query_date: string,
         *       nmUUID: string,
         *       subjectID: string,
         *       subjectName: string,
         *       vendorCode: string,
         *       brand: string,
         *       title: string,
         *       description: string,
         *       video: string,
         *       photos: string,
         *       dimensions_length: string,
         *       dimensions_width: string,
         *       dimensions_height: string,
         *       characteristics: string,
         *       sizes: string,
         *       createdAt: string,
         *       updatedAt: string,
         *       jCard: string,
         *       md5Card: string,
         *       tags: string
         *   }[] $products
         */
        $products = $this->connection->executeQuery(
            sql: "select * from wb.cards"
        )->fetchAllAssociative();

        return array_map(static fn(array $product): WbProductDto => new WbProductDto(
            attributes: new WbProductAttributeDto(
                sellerName: $product['seller_name'],
                nmId: (int)$product['nmID'],
                imtId: (int)$product['imtID'],
                vendorCode: $product['vendorCode'],
                brand: $product['brand'],
                subjectName: $product['subjectName'],
                subjectId: (int)$product['subjectID'],
                title: $product['title'],
                nmUuid: $product['nmUUID'],
                description: $product['description'],
                wbUpdateAt: $product['updatedAt'],
                tags: isset($product['tags'])
                    ? (array)json_decode($product['tags'], null, 512, JSON_THROW_ON_ERROR)
                    : null,
            ),
            dimensions: new WbProductDimensionsDto(
                length: $product['dimensions_length'],
                width: $product['dimensions_width'],
                height: $product['dimensions_height']),
            sizes: WbProductSizesDto::fromString($product['sizes']),
            media: WbProductMediaDto::fromString(photos: $product['photos'], video: $product['video']),
            characteristics: WbProductCharacteristicsDto::fromString($product['characteristics'])
        ), $products);
    }

    /**
     * @throws Exception
     */
    public function getColorDictionary(): SuggestDto
    {
        $dictionaries = $this->connection->executeQuery(
            sql: "select c.color_name from wb.colors c"
        )->fetchFirstColumn();

        return new SuggestDto(value: $dictionaries);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getKindDictionary(): array
    {
        $dictionaries = $this->connection->executeQuery(
            sql: "select c.kind_name from wb.kinds c"
        )->fetchAllAssociative();

        return array_map(static fn(array $dict): SuggestDto => new SuggestDto(
            value: (array)$dict['kind_name']
        ), $dictionaries);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getCountriesDictionary(): array
    {
        $dictionaries = $this->connection->executeQuery(
            sql: "select c.country_name, c.country_fullname from wb.countries c"
        )->fetchAllAssociative();

        return array_map(static fn(array $dict): SuggestDto => new SuggestDto(
            value: (array)$dict['country_name'],
            info: $dict['country_fullname']
        ), $dictionaries);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getSeasonsDictionary(): array
    {
        $dictionaries = $this->connection->executeQuery(
            sql: "select c.season_name from wb.seasons c"
        )->fetchAllAssociative();

        return array_map(static fn(array $dict): SuggestDto => new SuggestDto(
            value: (array)$dict['season_name']
        ), $dictionaries);
    }

    /**
     * @throws Exception
     */
    public function getTnvedDictionary(): SuggestDto
    {
        $dictionaries = $this->connection->executeQuery(
            sql: "select c.tnvedName from wb.tnved c"
        )->fetchFirstColumn();

        return new SuggestDto(value: $dictionaries);
    }

    /**
     * @throws Exception
     */
    public function getPrice(): array
    {
        $prices = $this->connection->executeQuery(
            sql: "select c.nmId, c.price, c.discount, c.final_price, c.is_stock_available from wb.prices c"
        )->fetchAllAssociative();

        return array_map(static fn(array $price): PriceDto => new PriceDto(
            nmId: $price['nmId'],
            price: $price['price'],
            discount: $price['discount'],
            finalPrice: $price['final_price'],
            isStockAvailable: $price['is_stock_available']
        ), $prices);
    }

    /**
     * @param string $article
     * @param int $finalPrice
     * @return array
     * @throws Exception
     */
    public function getNetCost(string $article, int $finalPrice): array
    {
        $sql = 'SELECT wb.fn_Себестоимость ((SELECT 
                    :article AS Артикул, 
                    getdate() AS Дата, 
                    :finalPrice AS ЦенаПродажи
                for json path, include_null_values, without_array_wrapper))';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('article', $article);
        $stmt->bindValue('finalPrice', $finalPrice);

        return json_decode($stmt->executeQuery()->fetchOne(), true);
    }
}
