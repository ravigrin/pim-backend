<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Price\GetNetCost;

use Psr\Log\LoggerInterface;
use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Domain\Repository\Dwh\WbImportRepositoryInterface;
use Wildberries\Domain\Repository\ProductInterface;
use Wildberries\Infrastructure\Exception\ProductNotFound;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private LoggerInterface             $logger,
        private ProductInterface            $productRepository,
        private WbImportRepositoryInterface $wbImportRepository
    ) {
    }

    /**
     * @throws ProductNotFound
     */
    public function __invoke(Query $query): float
    {
        $product = $this->productRepository->findOneBy(['productId' => $query->productId]);
        if (!$product) {
            $msg = "Not Found Product with Id: {$query->productId}
            - Wildberries/Application/Query/Price/GetNetCost/Fetcher.php::invoke()";
            $this->logger->critical($msg);
            throw new ProductNotFound($msg);
        }

        $netCost = $this->wbImportRepository->getNetCost(
            article: $product->getVendorCode(),
            finalPrice: $query->finalPrice
        );

        if (!isset($netCost['Себестоимость'])) {
            $msg = "Not Found Net Cost for article: {$product->getVendorCode()}
            - Wildberries/Application/Query/Price/GetNetCost/Fetcher.php::invoke()";
            $this->logger->critical($msg);
            throw new ProductNotFound($msg);
        }

        return (float)$netCost['Себестоимость'];
    }
}
