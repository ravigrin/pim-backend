<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product\GetVendorCodeByUuid;

use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;
use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Domain\Repository\ProductAttributeInterface;
use Wildberries\Domain\Repository\ProductInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private LoggerInterface           $logger,
        private ProductInterface          $productRepository,
        private ProductAttributeInterface $productAttributeRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Query $query): ?string
    {
        if (!$this->productRepository->findOneBy(['productId' => $query->productId])) {
            $this->logger->critical("Not found WB Product by ID: $query->productId
            - module/wildberries/src/Application/Query/Product/GetCodeAndLinkByUuid/Fetcher.php::invoke()");
        }

        return $this->productAttributeRepository->getVendorCode($query->productId);
    }
}
