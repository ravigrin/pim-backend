<?php

namespace OneC\Domain\Service;

use OneC\Domain\Repository\NomenclatureInterface;
use Psr\Log\LoggerInterface;

final readonly class SetBarcodeService
{
    public function __construct(
        private NomenclatureInterface $nomenclatureRepository,
        private LoggerInterface       $logger,
    ) {
    }

    public function handel(string $nomenclatureId, string $barcode): void
    {
        $response = $this->nomenclatureRepository->pushBarcode(
            nomenclatureId: $nomenclatureId,
            barcode: $barcode
        );
        $this->logger->info(
            sprintf(
                'Push barcode: Product: %s Barcode: %s Response: %s',
                $nomenclatureId,
                $barcode,
                json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            )
        );
    }
}
