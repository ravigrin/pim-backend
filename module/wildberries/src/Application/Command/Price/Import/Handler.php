<?php

namespace Wildberries\Application\Command\Price\Import;

use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Price;
use Wildberries\Domain\Repository\Dwh\WbImportRepositoryInterface;
use Wildberries\Domain\Repository\PriceInterface;
use Wildberries\Domain\Repository\ProductInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private PersistenceInterface        $persistenceRepository,
        private WbImportRepositoryInterface $wbImportRepository,
        private PriceInterface              $priceRepository,
        private ProductInterface            $productRepository
    ) {
    }

    public function __invoke(Command $command): void
    {
        foreach ($this->wbImportRepository->getPrice() as $price) {
            $product = $this->productRepository->findOneBy(['nmId' => $price->nmId]);
            if(!$product) {
                continue;
            }
            $localPrice = $this->priceRepository->findOneBy(['productId' => $product->getProductId()]);
            if (!$localPrice) {
                $localPrice = new Price(
                    productId: $product->getProductId(),
                    price: $price->price,
                    discount: $price->discount,
                    finalPrice: $price->finalPrice,
                    isStockAvailable: $price->isStockAvailable,
                );
                $this->persistenceRepository->persist($localPrice);
            }
            $localPrice->setPrice($price->price);
            $localPrice->setDiscount($price->discount);
            $localPrice->setFinalPrice($price->finalPrice);
            $localPrice->setIsStockAvailable($price->isStockAvailable);
            $this->persistenceRepository->persist($localPrice);
        }
        $this->persistenceRepository->flush();
    }


}
