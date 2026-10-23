<?php

declare(strict_types=1);

namespace Ozon\Application\Command\ProductImport;

use Ozon\Domain\Entity\Product;
use Ozon\Domain\Repository\AttributeInterface;
use Ozon\Domain\Repository\CatalogInterface;
use Ozon\Domain\Repository\Dwh\OzonImportInterface;
use Ozon\Domain\Repository\Internal\PimModuleInterface;
use Ozon\Domain\Repository\ProductInterface;
use Ozon\Domain\Service\AddProductAttribute;
use Pim\Domain\Repository\Pim\UserInterface;
use Psr\Log\LoggerInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Exception\ValueObjectException;
use Shared\Domain\Service\EntityStoreService;
use Shared\Domain\ValueObject\Uuid;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private OzonImportInterface $ozonRepository,
        private AttributeInterface  $attributeRepository,
        private ProductInterface    $productRepository,
        private CatalogInterface    $catalogRepository,
        private EntityStoreService  $entityStoreService,
        private UserInterface       $userRepository,
        private PimModuleInterface  $pimModuleRepository,
        private AddProductAttribute $addProductAttribute,
        private LoggerInterface     $loggerRepository,
    ) {
    }

    /**
     * @throws ValueObjectException
     */
    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findByUsername('system');
        if (is_null($user)) {
            return;
        }

        $ozonProducts = $this->ozonRepository->findProducts();
        foreach ($ozonProducts as $ozonProduct) {

            /** @psalm-param null|string $productId */
            $productId = $this->pimModuleRepository->findUuidByVendorCode($ozonProduct->offerId);
            if (is_null($productId)) {
                $this->loggerRepository->alert(
                    sprintf('Product %s not found in Pim', $ozonProduct->offerId)
                );
                continue;
            }

            echo $ozonProduct->name . PHP_EOL;

            $product = $this->productRepository->findOneByCriteria(["productUuid" => $productId,]);
            if (is_null($product)) {

                $catalog = $this->catalogRepository->findOneByCriteria(
                    [
                        'catalogId' => $ozonProduct->descriptionCategoryId,
                        'typeId' => $ozonProduct->typeId
                    ]
                );

                if (is_null($catalog)) {
                    $this->loggerRepository->alert(
                        sprintf('Catalog %s not found in Ozon', $ozonProduct->descriptionCategoryId)
                    );
                    continue;
                }
                $product = new Product(
                    productUuid: Uuid::fromString($productId),
                    userUuid: Uuid::fromString($user->getUserId()),
                    offerId: $ozonProduct->offerId,
                    barcode: $ozonProduct->barcode,
                    ozonProductId: (int)$ozonProduct->productId,
                    catalogUuid: $catalog->getCatalogUuid(),
                    export: 2
                );
            }

            foreach ($ozonProduct->attributes as $ozonAttribute) {

                $attribute = $this->attributeRepository->findByCatalogType(
                    catalogId: (int)$ozonProduct->descriptionCategoryId,
                    typeId: (int)$ozonProduct->typeId,
                    attributeId: $ozonAttribute->attribute_id,
                );

                if (is_null($attribute)) {
                    continue;
                }

                $values = [];
                foreach ($ozonAttribute->values as $value) {
                    $values[] = $value->value;
                }

                $this->addProductAttribute->handler(
                    product: $product,
                    attribute: $attribute,
                    user: $user,
                    value: $values,
                    prepareValue: (array)$ozonAttribute
                );
            }

            $attributes = (array)$ozonProduct;
            unset($attributes['attributes']);

            $attributes['publicProductLink'] = sprintf(
                'https://ozon.ru/product/%s',
                $ozonProduct->productId
            );
            $attributes['privateProductLink'] = sprintf(
                'https://seller.ozon.ru/app/products/%s/edit/general-info',
                $ozonProduct->productId
            );

            foreach ($attributes as $alias => $value) {
                $attribute = $this->attributeRepository->findOneByCriteria([
                    "alias" => $alias
                ]);
                if (is_null($attribute)) {
                    continue;
                }
                if (is_scalar($value)) {
                    $value = [$value];
                }
                $this->addProductAttribute->handler(
                    product: $product,
                    attribute: $attribute,
                    user: $user,
                    value: $value,
                    prepareValue: []
                );
            }
            $this->entityStoreService->commit($product);

            // обновляем статус в модуле pim
            $this->pimModuleRepository->setPimStatusMarketplace(
                productId: $product->getProductUuid()
            );
        }
    }

}
