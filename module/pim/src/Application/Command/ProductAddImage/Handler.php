<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductAddImage;

use Pim\Domain\Repository\Pim\ProductInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private ProductInterface   $productRepository,
        private EntityStoreService $entityStoreService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Command $command): void
    {
        $product = $this->productRepository->findOneByCriteria(
            ['productId' => $command->productId]
        );

        if (is_null($product)) {
            throw new \Exception('ProductAddImage: product not found');
        }

        $this->entityStoreService->commit($product);
    }
}
