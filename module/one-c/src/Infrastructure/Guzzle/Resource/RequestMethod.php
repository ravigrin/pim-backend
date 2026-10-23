<?php

declare(strict_types=1);

namespace OneC\Infrastructure\Guzzle\Resource;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

final class RequestMethod
{
    private string $odata;
    private string $hs;
    private string $authHeader;

    public function __construct(
        private LoggerInterface $logger,
    ) {
        $this->odata = $_ENV['ONE_C_PATH_ODATA'];
        $this->hs = $_ENV['ONE_C_PATH_HS'];
        // Basic-доступ к OData берётся из переменных окружения (см. .env.example)
        $this->authHeader = 'Basic ' . base64_encode(
            ($_ENV['ODATA_USER'] ?? '') . ':' . ($_ENV['ODATA_PASSWORD'] ?? '')
        );
    }

    /**
     * @var array<string, string>
     */
    private const DEFAULT_NOMENCLATURE_VALUES = [
        'ЕдиницаИзмерения_Key' => 'c4530313-09fb-11ee-a5e2-0cc47ab29cf7',
        'ВариантОформленияПродажи' => 'РеализацияТоваровУслуг',
        'СтавкаНДС_Key' => 'd0b92da9-09fb-11ee-a5e2-0cc47ab29cf7',
        'СтранаПроисхождения_Key' => '8d8d2e3c-09fb-11ee-a5e2-0cc47ab29cf7',
        'ИспользованиеХарактеристик' => 'НеИспользовать'
    ];

    /**
     * @var array<string, string>
     */
    private const PATH = [
        'newNomenclature' => 'Catalog_Номенклатура',
        'updateNomenclature' => 'Catalog_Номенклатура(guid\'%s\')?$format=json',
        'newBrand' => 'Catalog_Марки',
        'updateBrand' => 'Catalog_Марки(guid\'%s\')?$format=json'
    ];

    /**
     * @return array<string, string>
     */
    private function headers(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => $this->authHeader,
        ];
    }

    /**
     * @throws \JsonException
     */
    public function saveNomenclature(
        string  $nomenclatureId,
        string  $brandId,
        string  $nomenclatureName,
        string  $brandName,
        string  $vendorCode,
        bool    $isKit,
        bool    $isUpdate,
        ?string $productLineName,
    ): RequestInterface {

        // у нас есть бренд и подбренд (продуктовая линейка)
        // если у товара есть продуктовая линейка то отправлять ее имя в названии
        $beforeName = $productLineName;
        if (is_null($beforeName)) {
            $beforeName = $brandName;
        }

        $values = [
            'Ref_Key' => $nomenclatureId,
            'Description' => sprintf('%s %s', trim($beforeName), trim($nomenclatureName))
        ];

        $values['Марка_Key'] = $brandId;

        $nomenclatureType = [
            'product' => 'bcab7860-0e6c-11ee-890a-ac1f6b72b9b1',
            'kit' => 'cd956001-5c42-11ee-8c23-d7f482c7099f'
        ];

        if ($isKit) {
            $values['ВидНоменклатуры_Key'] = $nomenclatureType['kit'];
        } else {
            $values['ВидНоменклатуры_Key'] = $nomenclatureType['product'];
        }

        $values['Артикул'] = $vendorCode;

        $body = [...$values, ...self::DEFAULT_NOMENCLATURE_VALUES];

        if ($isUpdate) {
            $method = 'PATCH';
            $path = sprintf(self::PATH['updateNomenclature'], $nomenclatureId);
        } else {
            $method = 'POST';
            $path = self::PATH['newNomenclature'];
        }

        $this->logger->info(
            sprintf('Request to 1c: %s %s', $values['Артикул'], $values['Description'])
        );

        return new Request(
            method: $method,
            uri: $this->odata . $path,
            headers: $this->headers(),
            body: json_encode($body, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @throws \JsonException
     */
    public function saveBrand(string $brandId, string $brandName, bool $isUpdate): RequestInterface
    {
        $body = [
            "Ref_Key" => $brandId,
            "Description" => $brandName
        ];

        if ($isUpdate) {
            $method = 'PATCH';
            $path = sprintf(self::PATH['updateBrand'], $brandId);
        } else {
            $method = 'POST';
            $path = self::PATH['newBrand'];
        }

        return new Request(
            method: $method,
            uri: $this->odata . $path,
            headers: $this->headers(),
            body: json_encode($body, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @throws \JsonException
     */
    public function saveBarcode(string $nomenclatureId, string $barcode): RequestInterface
    {
        $body = [
            "barcode" => $barcode,
            "guid" => $nomenclatureId
        ];

        return new Request(
            method: 'POST',
            uri: $this->hs . 'SetBarcode',
            headers: $this->headers(),
            body: json_encode($body, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @param string[] $products
     * @throws \JsonException
     */
    public function saveKit(string $nomenclatureId, array $products): RequestInterface
    {
        // Реализация отложена: OData-ручка комплекта ещё не готова (см. TODO.md).
        $body = [
            "products" => $products,
            "guid" => $nomenclatureId
        ];

        return new Request(
            method: 'POST',
            uri: '',
            headers: $this->headers(),
            body: ''
        );
    }

    public function findNomenclatureByGuid(string $guid): RequestInterface
    {
        $path = sprintf('Catalog_Номенклатура(guid\'%s\')?$format=json', $guid);

        return new Request(
            method: 'GET',
            uri: $this->odata . $path,
            headers: $this->headers(),
        );
    }

    public function findBrandByGuid(string $guid): RequestInterface
    {
        $path = sprintf('Catalog_Марки(guid\'%s\')?$format=json', $guid);

        return new Request(
            method: 'GET',
            uri: $this->odata . $path,
            headers: $this->headers(),
        );
    }
}
