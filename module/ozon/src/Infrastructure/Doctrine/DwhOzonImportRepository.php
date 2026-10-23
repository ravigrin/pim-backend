<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;
use Ozon\Domain\Repository\Dwh\Dto\OzonAttributeDto;
use Ozon\Domain\Repository\Dwh\Dto\OzonCatalogDto;
use Ozon\Domain\Repository\Dwh\Dto\OzonDictionaryDto;
use Ozon\Domain\Repository\Dwh\Dto\Product\Import\AttributeDto;
use Ozon\Domain\Repository\Dwh\Dto\Product\Import\ProductDto;
use Ozon\Domain\Repository\Dwh\Dto\Product\Import\ValueDto;
use Ozon\Domain\Repository\Dwh\OzonImportInterface;

use function PHPUnit\Framework\callback;

class DwhOzonImportRepository implements OzonImportInterface
{
    private Connection $connection;

    public function __construct(
        private ManagerRegistry $doctrine
    ) {
        /** @var Connection $dwh */
        $dwh = $this->doctrine->getConnection('dwh');
        $this->connection = $dwh;
    }

    /**
     * @return OzonCatalogDto[]
     * @throws Exception
     */
    public function findCatalogsByTreePath(string $treePath): array
    {
        /** @var array{
         *     parent_category_id: string,
         *     title: string,
         *     tree_path: string,
         *     level: string,
         *     type_id: string,
         *     type_name: string
         * }[] $ozonCatalogs
         */
        $ozonCatalogs = $this->connection
            ->executeQuery(
                sql: "select * from ozon.ref_description_category_tree where level = 3 and tree_path like '%Красота и гигиена%'",
                params: [$treePath],
                types: [ParameterType::STRING]
            )
            ->fetchAllAssociative();

        return array_map(static fn (array $catalog): OzonCatalogDto => new OzonCatalogDto(
            treePath: $catalog['tree_path'],
            level: (int)$catalog['level'],
            typeId: (int)$catalog['type_id'],
            parentCatalogId: (int)$catalog['parent_category_id'],
            typeName: $catalog['type_name'],
        ), $ozonCatalogs);
    }

    /**
     * @return OzonAttributeDto[]
     * @throws Exception
     */
    public function findAttributesByTypeId(int $typeId): array
    {
        /** @var array{
         *     category_id: string,
         *     type_id: string,
         *     attribute_id: string,
         *     attribute_complex_id: string,
         *     name: string,
         *     description: string,
         *     type: string,
         *     is_collection: string,
         *     is_required: string,
         *     is_aspect: string,
         *     max_value_count: string,
         *     group_name: string,
         *     group_id: string,
         *     dictionary_id: string
         * }[] $ozonAttributes
         */
        $ozonAttributes = $this->connection
            ->executeQuery(
                sql: "select * from ozon.ref_description_category_attribute where type_id = ?",
                params: [$typeId],
            )
            ->fetchAllAssociative();

        return array_map(static fn (array $ozonAttribute): OzonAttributeDto => new OzonAttributeDto(
            categoryId: (int)$ozonAttribute['category_id'],
            typeId: (int)$ozonAttribute['type_id'],
            attributeId: (int)$ozonAttribute['attribute_id'],
            attributeComplexId: (int)$ozonAttribute['attribute_complex_id'],
            name: $ozonAttribute['name'],
            description: $ozonAttribute['description'],
            type: $ozonAttribute['type'],
            isCollection: (bool)$ozonAttribute['is_collection'],
            isRequired: (bool)$ozonAttribute['is_required'],
            isAspect: (bool)$ozonAttribute['is_aspect'],
            maxValueCount: (int)$ozonAttribute['max_value_count'],
            groupName: $ozonAttribute['group_name'],
            groupId: (int)$ozonAttribute['group_id'],
            dictionaryId: (int)$ozonAttribute['dictionary_id'],
        ), $ozonAttributes);
    }

    /**
     * @return OzonDictionaryDto[]
     * @throws Exception
     */
    public function findDictionary(int $catalogId, int $attributeId): array
    {
        /** @var array{
         *     category_id: string,
         *     attribute_id: string,
         *     attribute_value_id: string,
         *     value: string,
         *     info: string,
         *     picture: string,
         * }[] $ozonDictionary
         */
        $ozonDictionary = $this->connection
            ->executeQuery(
                sql: "select * from ozon.ref_category_attribute_values where category_id = ? and attribute_id = ?",
                params: [$catalogId, $attributeId],
            )
            ->fetchAllAssociative();

        return array_map(static fn (array $dictionary): OzonDictionaryDto => new OzonDictionaryDto(
            categoryId: (int)$dictionary['category_id'],
            attributeId: (int)$dictionary['attribute_id'],
            dictionaryId: (int)$dictionary['attribute_value_id'],
            value: $dictionary['value'],
            info: $dictionary['info'],
            picture: $dictionary['picture']
        ), $ozonDictionary);
    }

    /*
     "barcode": "112772873170",
    "category_id": 17033876,
    "color_image": "",
    "complex_attributes": [ ],
    "currency_code": "RUB",
    "depth": 10,
    "dimension_unit": "mm",
    "height": 250,
    "images": [ ],
    "images360": [ ],
    "name": "Комплект защитных плёнок для X3 NFC. Темный хлопок",
    "offer_id": "143210608",
    "old_price": "1100",
    "pdf_list": [ ],
    "premium_price": "900",
    "price": "1000",
    "primary_image": "",
    "vat": "0.1",
    "weight": 100,
    "weight_unit": "g",
    "width": 150
     */

    /**
     * @return ProductDto[]
     * @throws Exception
     */
    public function findProducts(): array
    {
        /** @var array{
         *     client_id: string,
         *     product_id: string,
         *     attributes: array,
         *     complex_attributes: string,
         *     barcode: string,
         *     description_category_id: string,
         *     color_image: string,
         *     currency_code: string,
         *     offer_id: string,
         *     name: string,
         *     category_id: string,
         *     images: string,
         *     marketing_price: string,
         *     min_ozon_price: string,
         *     old_price: string,
         *     premium_price: string,
         *     price: string,
         *     recommended_price: string,
         *     min_price: string,
         *     vat: string,
         *     visible: string,
         *     images360: string,
         *     primary_image: string,
         *     offer_id: string,
         *     height: string,
         *     depth: string,
         *     width: string,
         *     dimension_unit: string,
         *     weight: string,
         *     weight_unit: string,
         *     image_group_id: string,
         *     pdf_list: string,
         * }[] $ozonProducts
         */
        $ozonProducts = $this->connection
            ->executeQuery(
                sql: "select * from ozon.products as pr 
                        inner join ozon.product_attributes as att on pr.product_id = att.product_id
                        where pr.client_id = 1090191",
            )->fetchAllAssociative();

        return array_map(static function (array $ozonProduct): ProductDto {
            /** @var string[] $images */
            $images = json_decode((string)$ozonProduct['images'], true);
            /** @var string[] $images360 */
            $images360 = json_decode((string)$ozonProduct['images360'], true);

            $attributes = [];
            /** @var array{attribute_id: string, complex_id: string, values: array} $attribute */
            foreach (json_decode($ozonProduct['attributes'], true) as $attribute) {
                $values = [];
                /** @var array{dictionary_value_id: int, value: string} $value */
                foreach ($attribute['values'] as $value) {
                    $values[] = new ValueDto(
                        dictionary_value_id: $value['dictionary_value_id'],
                        value: $value['value']
                    );
                }
                $attributes[] = new AttributeDto(
                    attribute_id: $attribute['attribute_id'],
                    complex_id: $attribute['complex_id'],
                    values: $values
                );
            }

            /** @var string[] $complexAttributes */
            $complexAttributes = json_decode((string)$ozonProduct['complex_attributes'], true);

            return new ProductDto(
                clientId: $ozonProduct['client_id'],
                productId: $ozonProduct['product_id'],
                offerId: $ozonProduct['offer_id'],
                name: $ozonProduct['name'],
                barcode: $ozonProduct['barcode'],
                descriptionCategoryId: $ozonProduct['description_category_id'],
                typeId: $ozonProduct['type_id'],
                images: $images,
                oldPrice: $ozonProduct['old_price'],
                premiumPrice: $ozonProduct['premium_price'],
                price: $ozonProduct['price'],
                vat: $ozonProduct['vat'],
                images360: $images360,
                colorImage: $ozonProduct['color_image'],
                primaryImage: $ozonProduct['primary_image'],
                currencyCode: $ozonProduct['currency_code'],
                height: $ozonProduct['height'],
                depth: $ozonProduct['depth'],
                width: $ozonProduct['width'],
                dimensionUnit: $ozonProduct['dimension_unit'],
                weight: $ozonProduct['weight'],
                weightUnit: $ozonProduct['weight_unit'],
                pdfList: $ozonProduct['pdf_list'],
                attributes: $attributes,
                complexAttributes: $complexAttributes,
            );
        }, $ozonProducts);
    }

}
