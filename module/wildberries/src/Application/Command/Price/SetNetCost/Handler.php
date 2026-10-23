<?php

namespace Wildberries\Application\Command\Price\SetNetCost;

use Psr\Log\LoggerInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Repository\Dwh\WbImportRepositoryInterface;
use Wildberries\Domain\Repository\PriceInterface;
use Wildberries\Domain\Repository\ProductInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private WbImportRepositoryInterface $wbImportRepository,
        private PersistenceInterface        $persistenceRepository,
        private PriceInterface              $priceRepository,
        private ProductInterface            $productRepository,
        private LoggerInterface             $logger
    )
    {
    }

    public function __invoke(Command $command): void
    {
        foreach ($this->priceRepository->findBy([]) as $price) {
            $product = $this->productRepository->findOneBy(['productId' => $price->getProductId()]);
            if (!$product || !$product->getVendorCode()) {
                continue;
            }

            $netCost = $this->wbImportRepository->getNetCost($product->getVendorCode(), $price->getFinalPrice());

            if (!isset($netCost['Себестоимость']) || !isset($netCost['СебестоимостьПроизводства'])
                || isset($netCost['СебестоимостьПроизводстваЗадана'])) {
                $this->logger->warning("Not found net cost price for article: {$product->getVendorCode()}
                - Wildberries/Application/Command/Price/SetNetCost/Handler.php::invoke()");
            }

            $price->setNetCost($netCost['Себестоимость']);
            $price->setProductionCost($netCost['СебестоимостьПроизводства']);
            $price->setProductionCostFlag($netCost['СебестоимостьПроизводстваЗадана']);
            $this->persistenceRepository->persist($price);
        }
        $this->persistenceRepository->flush();
    }

}
