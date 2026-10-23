<?php

namespace Wildberries\Infrastructure\Service;

use Pim\Application\Query\Attribute\GetValueByProductAlias\Query as GetValueByProductAliasQuery;
use Shared\Domain\Query\QueryBusInterface;
use Pim\Application\Query\Product\GetByUuid\Query as PimGetByUuid;
use Wildberries\Application\Query\Product\ProductFullDto;
use Wildberries\Domain\Entity\Product;
use Wildberries\Domain\Repository\AttributeMapInterface;
use Wildberries\Domain\Repository\CatalogInterface;
use Wildberries\Domain\Repository\Dto\Export\WbCreateProductDto;
use Wildberries\Domain\Repository\Dto\Export\WbCreateProductItemDto;
use Wildberries\Domain\Repository\Dto\Export\WbProductDimensionsDto;
use Wildberries\Domain\Repository\Dto\Export\WbUpdateProductDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductSizesDto;
use Wildberries\Domain\Repository\ProductAttributeInterface;
use Wildberries\Domain\Repository\SizeInterface;
use Wildberries\Infrastructure\Service\Exception\ProductBuildFail;
use Psr\Log\LoggerInterface;

/**
 * Сервис для работы с товарами Wildberries
 */
readonly class ProductService
{
    public function __construct(
        private LoggerInterface           $logger,
        private QueryBusInterface         $queryBus,
        private CatalogInterface          $catalogRepository,
        private ProductAttributeInterface $productAttributeRepository,
        private AttributeMapInterface     $attributeMapRepository,
        private SizeInterface             $sizeRepository
    )
    {
    }

    /**
     * Собирает продукт для отправки на МП
     * @throws ProductBuildFail
     */
    public function build(Product $product): WbCreateProductDto|WbUpdateProductDto|bool
    {
        $productCategory = $this->catalogRepository->findOneBy([
            'catalogId' => $product->getCatalogId()
        ]);
        if (!$productCategory) {
            $this->logger->critical("Для товара id: " . $product->getProductId() . " не найдена категория");
            return false;
        }

        if (empty($characteristics = $this->getCharacteristics($product, $productCategory->getName()))) {
            $this->logger->critical("Товар id: " . $product->getProductId() . " не имеет характеристик");
            return false;
        }

        // Если у продукта есть номенклатурный номер WB - Формируем DTO на обновление товара
        if ($nmId = $product->getNmId()) {
            if (!$sizes = $this->sizeRepository->findBy(['productId' => $product->getProductId()])) {
                $this->logger->critical("Для товара id: " . $product->getProductId() . " не установлены размеры");
                return false;
            }

            return new WbUpdateProductDto(
                nmId: $nmId,
                vendorCode: $product->getVendorCode() ?? throw new ProductBuildFail('Not Found VendorCode for exist product'),
                brand: $product->getBrand(),
                title: $product->getTitle(),
                description: $product->getDescription(),
                dimensions: $product->getDimensions(),
                characteristics: $characteristics,
                sizes: WbProductSizesDto::fromArray($sizes)
            );

            // Обновление медиа-контента отдельным методом — см. TODO.md.
        }

        // Формируем DTO на создание нового товара
        $alias = 'ШК товара';
        $sku = $this->queryBus->dispatch(new GetValueByProductAliasQuery(
            $product->getProductId(),
            $alias
        ));
        if (!$sku || !is_string($sku)) {
            $this->logger->critical("Не указан ШК для товара id: " . $product->getProductId());
            return false;
        }

        $alias = 'РРЦ (руб)';
        $price = $this->queryBus->dispatch(new GetValueByProductAliasQuery(
            $product->getProductId(),
            $alias
        ));

        if (!$price || !is_int($price)) {
            $this->logger->critical("Не указана РРЦ для товара id: " . $product->getProductId());
        }

        $dimensions = $product->getDimensions();

        return new WbCreateProductDto(
            subjectId: $product->getCatalogId(),
            variants: [new WbCreateProductItemDto(
                nmId: $product->getNmId(),
                vendorCode: $product->getVendorCode(),
                brand: $product->getBrand(),
                title: $product->getTitle(),
                description: $product->getDescription(),
                dimensions: new WbProductDimensionsDto(
                    length: $dimensions['length'],
                    width: $dimensions['width'],
                    height: $dimensions['height']
                ),
                characteristics: $characteristics,
                sizes: WbProductSizesDto::fromArray(
                    $this->sizeRepository->findBy(['productId' => $product->getProductId()])
                )
            )]
        );
    }

    /**
     * Возвращает подготовленный массив характеристик продукта
     * @param Product $product Товар
     * @param string $object Наименование предмета (категории)
     */
    private function getCharacteristics(Product $product, string $object): array
    {
        $resp = [];

        $characteristics = $this->productAttributeRepository->getCharacteristics($product->getProductId());
        if (empty($characteristics)) {
            return $resp;
        }
        $resp[]['Предмет'] = $object;
        foreach ($characteristics as $charc) {
            // НЕ форматить - с тернарным все ломается
            if (is_string($charc->value) && json_decode($charc->value)) {
                $value = json_decode($charc->value);
            } else {
                $value = $charc->value;
            }

            $resp[] = [$charc->name => $value];
        }

        return $resp;
    }

    /**
     * Возвращает шаблон товара со значениями атрибутов как в PIM (расширение — см. TODO.md)
     * @param string $productId
     * @return ProductFullDto|false
     */
    public function getProductTemplate(string $productId): ProductFullDto|false
    {
        if (!$pimProduct = $this->queryBus->dispatch(new PimGetByUuid($productId))) {
            $this->logger->critical("NOT Found PIM product with id: {$productId}
            - Wildberries/Infrastructure/Service/ProductService.php::getProductTemplate()");
            return false;
        }

        $attributes = [];

        foreach ($pimProduct->attributes as $pimAttribute) {

            if (!$attributeMap = $this->attributeMapRepository->findoneBy([
                'pimAttributeId' => $pimAttribute['attributeId']])) {
                continue;
            }

            $attributes[] = [
                'attributeId' => $attributeMap->getWbAttributeId(),
                'value' => $pimAttribute['value']
            ];
        }

        return new ProductFullDto(
            productId: $productId,
            attributes: $attributes
        );
    }

}
