<?php

declare(strict_types=1);

namespace OneC\Infrastructure\Guzzle;

use OneC\Domain\Repository\NomenclatureInterface;
use OneC\Infrastructure\Guzzle\Resource\HttpClient;
use OneC\Infrastructure\Guzzle\Resource\RequestMethod;
use Psr\Http\Client\ClientExceptionInterface;

final readonly class NomenclatureRepository implements NomenclatureInterface
{
    public function __construct(
        private RequestMethod $requestMethod,
        private HttpClient    $httpClient
    ) {
    }

    /**
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function exportBrand(string $brandId, string $brandName, bool $isUpdate): void
    {
        $this->httpClient->request(
            $this->requestMethod->saveBrand($brandId, $brandName, $isUpdate)
        );
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function isNomenclatureUpload(string $guid): bool
    {
        $content = $this->httpClient->request(
            $this->requestMethod->findNomenclatureByGuid($guid)
        );
        return isset($content['Ref_Key']);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function isBrandUpload(string $brandId): bool
    {
        $content = $this->httpClient->request(
            $this->requestMethod->findBrandByGuid($brandId)
        );
        return isset($content['Ref_Key']);
    }

    public function pushNomenclature(
        string  $nomenclatureId,
        string  $brandId,
        string  $nomenclatureName,
        string  $brandName,
        string  $vendorCode,
        bool    $isKit,
        bool    $isUpdate,
        ?string $productLineName,
    ): void {
        $this->httpClient->request(
            $this->requestMethod->saveNomenclature(
                nomenclatureId: $nomenclatureId,
                brandId: $brandId,
                nomenclatureName: $nomenclatureName,
                brandName: $brandName,
                vendorCode: $vendorCode,
                isKit: $isKit,
                isUpdate: $isUpdate,
                productLineName: $productLineName,
            )
        );
    }

    /**
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function pushBarcode(string $nomenclatureId, string $barcode): string
    {
        /** @var array{res: string} $response */
        $response = $this->httpClient->request(
            $this->requestMethod->saveBarcode(
                nomenclatureId: $nomenclatureId,
                barcode: $barcode
            )
        );
        return $response['res'];
    }

    public function pushKit(string $nomenclatureId, array $products): string
    {
        /** @var array{res: string} $response */
//        $response = $this->httpClient->request(
//            $this->requestMethod->saveKit(
//                nomenclatureId: $nomenclatureId,
//                products: $products
//            )
//        );
        return 'Ручки нет, жду Сашу';
    }
}
