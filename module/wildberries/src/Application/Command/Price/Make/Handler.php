<?php

namespace Wildberries\Application\Command\Price\Make;

use Psr\Log\LoggerInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Application\Command\Price\Export\PriceDto;
use Wildberries\Domain\Entity\Price;
use Wildberries\Domain\Repository\Dwh\WbExportRepositoryInterface;
use Wildberries\Domain\Repository\PriceInterface;
use Wildberries\Domain\Repository\ProductInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private PersistenceInterface        $persistenceRepository,
        private WbExportRepositoryInterface $wbExportRepository,
        private PriceInterface              $priceRepository,
        private ProductInterface            $productRepository,
        private LoggerInterface             $logger
    )
    {
    }

    public function __invoke(Command $command): void
    {
        /**
         * @var $prices PriceDto[]
         */
        $prices = [];

        $seller = match ($_ENV['WB_ENV']) {
            "test", "dev", "preprod" => "Marketplace Seller",
            "prod" => "Marketplace Seller"
        };

        foreach ($command->prices as $price) {
            $product = $this->productRepository->findOneBy(['productId' => $price->productId]);
            if (!$product) {
                $this->logger->alert("Not found product by Id: {$price->productId}
                - Wildberries/Application/Command/Price/Make/Handler.php::invoke()");
                continue;
            }

            $localPrice = $this->priceRepository->findOneBy(['productId' => $product->getProductId()]);
            if (!$localPrice) {
                $localPrice = new Price(
                    productId: $price->productId,
                    price: $price->price,
                    discount: $price->discount,
                    finalPrice: $price->totalPrice,
                    isStockAvailable: false,
                    netCost: $price->costPrice
                );
                $this->persistenceRepository->persist($localPrice);
            }
            $localPrice->setPrice($price->price);
            $localPrice->setDiscount($price->discount);
            $localPrice->setFinalPrice($price->totalPrice);
            $localPrice->setNetCost($price->costPrice);
            $this->persistenceRepository->persist($localPrice);

            $prices[] = new PriceDto(
                nmId: $product->getNmId(),
                price: $price->price
            );
        }
        $this->persistenceRepository->flush();

        // Отправляем обновление цен через DWH в WB
        if(!empty($prices)) {
            $this->wbExportRepository->priceUpdate($prices, $seller);
        }
    }


}
