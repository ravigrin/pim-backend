<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductSetMarketplace;

use Pim\Domain\Repository\Pim\AttributeInterface;
use Pim\Domain\Repository\Pim\ProductInterface;
use Pim\Domain\Repository\Pim\UserInterface;
use Pim\Domain\Service\EditProductAttribute;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private UserInterface        $userRepository,
        private ProductInterface     $productRepository,
        private AttributeInterface   $attributeRepository,
        private EditProductAttribute $editProductAttribute,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findByUsername('system');
        if (is_null($user)) {
            return;
        }

        $product = $this->productRepository->findOneByCriteria(["productId" => $command->productId]);
        if (is_null($product)) {
            return;
        }

        $attribute = $this->attributeRepository->findOneByCriteria(["alias" => 'status']);
        if (is_null($attribute)) {
            return;
        }

        $this->editProductAttribute->handler(
            productId: $product->getProductId(),
            attributeId: $attribute->getAttributeId(),
            userId: $user->getUserId(),
            value: ["Маркетплейс"]
        );

    }
}
