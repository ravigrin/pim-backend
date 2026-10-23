<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Ozon\Domain\Entity\Product;
use Ozon\Domain\Entity\ProductAttribute;
use Ozon\Domain\Repository\CatalogInterface;
use Ozon\Domain\Repository\Dwh\Dto\Product\Export\ProductDto;
use Ozon\Domain\Repository\Dwh\Dto\Product\Export\ProductsDto;
use Ozon\Domain\Repository\Dwh\OzonExportInterface;
use Ozon\Domain\Repository\ProductAttributeInterface;

class DwhOzonExportRepository implements OzonExportInterface
{
    private Connection $connection;

    public function __construct(
        private ManagerRegistry           $doctrine,
        private ProductAttributeInterface $productAttributeRepository,
        private CatalogInterface          $catalogRepository,
    ) {
        /** @var Connection $dwh */
        $dwh = $this->doctrine->getConnection('dwh');
        $this->connection = $dwh;
    }

    /**
     * @throws \Exception
     */
    public function buildProduct(Product $product): ProductDto
    {
        $attributes = array_map(
            fn (ProductAttribute $productAttribute): array => $productAttribute->getPrepareValue(),
            $this->productAttributeRepository->findByNoAlias(
                productId: $product->getProductUuid(),
                catalogId: $product->getCatalogUuid()
            )
        );

        $catalog = $this->catalogRepository->findOneByCriteria([
            "catalogUuid" => $product->getCatalogUuid()
        ]);

        return new ProductDto(
            attributes: $attributes,
            barcode: (string)$this->getValue($product, 'barcode'),
            description_category_id: $catalog->getCatalogId(),
            color_image: "",
            complex_attributes: [],
            currency_code: $product->getCurrencyCode(),
            depth: (int)$this->getValue($product, 'depth'),
            dimension_unit: $product->getDimensionUnit(),
            height: (int)$this->getValue($product, 'height'),
            images: [],
            images360: [],
            name: $this->getValue($product, 'name'),
            offer_id: $product->getOfferId(),
            old_price: $this->getValue($product, 'oldPrice'),
            pdf_list: [],
            premium_price: ($this->getValue($product, 'premiumPrice')) ?? 0,
            price: $this->getValue($product, 'price'),
            primary_image: "",
            vat: $product->getVat(),
            weight: (int)$this->getValue($product, 'weight'),
            weight_unit: $product->getWeightUnit(),
            width: (int)$this->getValue($product, 'width'),
        );
    }

    private function getValue(Product $product, string $alias): null|string
    {
        $value = $this->productAttributeRepository
            ->findOneByProductAndAlias($product->getProductUuid(), $alias)
            ?->getValue();

        if (is_array($value)) {
            print_r([$alias, $value]);
        }
        if (is_null($value)) {
            echo 'null: ' . $alias;
        }
        return $value;
    }

    /**
     * @throws Exception
     */
    public function send(ProductsDto $productsDto): void
    {
        $json = json_encode((array)$productsDto);
        $this->connection
            ->executeQuery(
                sql: sprintf(
                    "exec ozon.send_products @ClientId = %s, @Data = '%s'",
                    $_ENV['OZON_CLIENT_ID'],
                    $json
                ),
            );
    }
}
