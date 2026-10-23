<?php

declare(strict_types=1);

namespace Ozon\Application\Command\ProductExport;

use Ozon\Domain\Repository\Dwh\Dto\Product\Export\ProductsDto;
use Ozon\Domain\Repository\Dwh\OzonExportInterface;
use Ozon\Domain\Repository\Ozon\ProductExportInterface;
use Ozon\Domain\Repository\ProductInterface;
use Psr\Log\LoggerInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private OzonExportInterface $exportRepository,
        private ProductExportInterface $productExport,
        private ProductInterface    $productRepository,
        private EntityStoreService  $entityStoreService,
        private LoggerInterface     $loggerRepository,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $product = $this->productRepository->findOneByCriteria([
            "productUuid" => $command->productId
        ]);

        if (is_null($product)) {
            //$this->loggerRepository->info("ProductExport Not found product: " . $command->productId);
            return;
        }

        if ($product->getExport() === 2) {
            //$this->loggerRepository->info("ProductExport status:UPLOAD stop push: " . $command->productId);
            return;
        }

        try {
            $buildProduct = (array)$this->exportRepository->buildProduct($product);
            $this->productExport->send(
                new ProductsDto([$buildProduct])
            );
            //$product->setExport(2);
        } catch (\Exception $exception) {
            $this->loggerRepository->error($exception);
            $product->setExport(3);
        }
        //$this->entityStoreService->commit($product);
    }
}
