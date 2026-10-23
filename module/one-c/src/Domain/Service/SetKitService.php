<?php

namespace OneC\Domain\Service;

use OneC\Domain\Repository\NomenclatureInterface;
use Psr\Log\LoggerInterface;

final readonly class SetKitService
{
    public function __construct(
        private NomenclatureInterface $nomenclatureRepository,
        private LoggerInterface       $logger,
    ) {
    }

    /**
     * @param string[] $products // uuid товаров/номенклатур которые входят в комплект
     */
    public function handler(string $nomenclatureId, array $products): void
    {
        $response = $this->nomenclatureRepository->pushKit(
            nomenclatureId: $nomenclatureId,
            products: $products
        );
        $this->logger->info(
            sprintf(
                'Push kit: Product: %s Response: %s',
                $nomenclatureId,
                json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            )
        );
    }

}
