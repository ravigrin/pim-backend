<?php

namespace Ozon\Infrastructure\Guzzle;

use GuzzleHttp\Psr7\Request;
use Ozon\Domain\Repository\Dwh\Dto\Product\Export\ProductsDto;
use Ozon\Domain\Repository\Ozon\ProductExportInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class ProductExportRepository implements ProductExportInterface
{
    private array $headers = [
        'Accept' => 'application/json',
        //'Host' => 'https://api-seller.ozon.ru',
    ];

    public function __construct(
        private ClientInterface $client,
        private LoggerInterface $logger,
    ) {
        $this->headers['Client-Id'] = $_ENV['OZON_CLIENT_ID'];
        $this->headers['Api-Key'] = $_ENV['OZON_API_KEY'];
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function send(ProductsDto $productsDto): void
    {
        $json = (string)json_encode((array)$productsDto, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        echo $json;

        $response = $this->client->sendRequest(
            new Request(
                method: 'POST',
                uri: 'https://api-seller.ozon.ru/v3/product/import',
                headers: $this->headers,
                body: $json
            )
        );

        $log = sprintf('Ozon send products. Response: %s', $response->getBody()->getContents());

        echo $log . PHP_EOL;

        $this->logger->info($log);
    }

    public function check(string $taskId): void
    {
        $response = $this->client->sendRequest(
            new Request(
                method: 'POST',
                uri: 'https://api-seller.ozon.ru/v1/product/import/info',
                headers: $this->headers,
                body: (string)json_encode(['task_id' => $taskId])
            )
        );

        $log = sprintf('Ozon check products. Response: %s', $response->getBody()->getContents());

        echo $log . PHP_EOL;

        $this->logger->info($log);
    }
}
