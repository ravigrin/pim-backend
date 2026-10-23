<?php

declare(strict_types=1);

namespace Ozon\Application\Command\ProductEdit;

use Ozon\Domain\Entity\Product;
use Ozon\Domain\Entity\ProductAttribute;
use Ozon\Domain\Repository\AttributeInterface;
use Ozon\Domain\Repository\ProductInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Exception\ValueObjectException;
use Shared\Domain\Repository\PersistenceInterface;
use Shared\Domain\ValueObject\Uuid;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private ProductInterface     $productRepository,
        private AttributeInterface   $attributeRepository,
        private PersistenceInterface $persistenceRepository,
    ) {
    }

    /**
     * @throws ValueObjectException
     */
    public function __invoke(Command $command): void
    {
        $product = $this->productRepository->findOneByCriteria(["productUuid" => $command->productId]);
        if (is_null($product)) {
            $product = new Product(
                productUuid: Uuid::fromString($command->productId),
                userUuid: Uuid::fromString($command->user->getUserId()),
                offerId: $command->vendorCode,
                catalogUuid: Uuid::fromString($command->catalogId),
                unification: $command->union
            );
        }

        $product->setUnification($command->union);
        $productsUnion = $this->productRepository->findByCriteria(["productUuid" => $command->union]);
        foreach ($productsUnion as $productUnion) {
            $productUnion->setUnification($command->union);
            $this->persistenceRepository->persist($productUnion);
        }
        $product->setExport(0);
        $this->persistenceRepository->persist($product);

        foreach ($command->attributes as $attributeData) {
            $attribute = $this->attributeRepository->findOneByCriteria([
                "attributeUuid" => $attributeData->attributeId
            ]);

            if (is_null($attribute)) {
                continue;
            }

            if (is_scalar($attributeData->value)) {
                $attributeData->value = [$attributeData->value];
            }

            $productAttribute = new ProductAttribute(
                productAttributeUuid: Uuid::build(),
                attributeUuid: new Uuid($attributeData->attributeId),
                productUuid: $product->getProductUuid(),
                userUuid: new Uuid($command->user->getUserId()),
                value: $attributeData->value,
                prepareValue: $attributeData->value
            );
            $this->persistenceRepository->persist($productAttribute);
        }
        $this->persistenceRepository->flush();
    }

}
