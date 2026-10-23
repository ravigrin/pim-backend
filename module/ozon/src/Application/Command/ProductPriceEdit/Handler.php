<?php

declare(strict_types=1);

namespace Ozon\Application\Command\ProductPriceEdit;

use Ozon\Domain\Entity\Attribute;
use Ozon\Domain\Repository\AttributeInterface;
use Ozon\Domain\Repository\Dwh\OzonPriceInterface;
use Ozon\Domain\Repository\ProductInterface;
use Pim\Domain\Repository\Pim\UserInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private EntityStoreService $entityStoreService,
        private ProductInterface   $productRepository,
        private AttributeInterface $attributeRepository,
        private UserInterface      $userRepository,
        private OzonPriceInterface $priceRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findByUsername($command->username);
        if (is_null($user)) {
            throw new \Exception("User not found");
        }

        foreach ($command->prices as $price) {

            $product = $this->productRepository->findOneByCriteria(["productUuid" => $price->productId]);

            $aliases = [
                "price" => $price->totalPrice,
                "oldPrice" => $price->price,
                "costPrice" => $price->costPrice,
                "salePercent" => $price->discount
            ];

            /** @var array{
             * attribute: Attribute,
             * value: float|int
             * }[] $attributes
             */
            $attributes = [];
            foreach ($aliases as $alias => $value) {
                $attribute = $this->attributeRepository->findOneByCriteria(["alias" => $alias]);
                if (is_null($attribute)) {
                    throw new \Exception(sprintf("ProductPriceEdit: attribute %s not found", $alias));
                }
                $attributes[] = [
                    'attribute' => $attribute,
                    'value' => $value
                ];
            }

            foreach ($attributes as $attribute) {
                $product->setAttribute($attribute['attribute'], $user, [$attribute['value']], []);
            }

            $this->entityStoreService->commit($product);

            // отправка на обновление цен в ozon
            $this->priceRepository->updatePrice(
                offerId: $product->getOfferId(),
                productId: $product->getOzonProductId(),
                minPrice: $price->totalPrice,
                oldPrice: $price->price,
                price: $price->totalPrice
            );
        }
    }

}
