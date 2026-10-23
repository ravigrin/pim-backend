<?php

declare(strict_types=1);

namespace Fulfillment\Infrastructure\Http;

use Fulfillment\Domain\Repository\Dto\OrderDataDto;
use Fulfillment\Domain\Repository\Dto\PushResultDto;
use Fulfillment\Domain\Repository\FulfillmentProviderInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Универсальный REST-адаптер фулфилмент-оператора.
 *
 * Новый оператор подключается конфигурацией в services.yaml; при
 * нестандартном контракте пишется своя реализация портов выше.
 */
final readonly class GenericRestProvider implements FulfillmentProviderInterface
{
    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private string $name,
        private string $baseUrl,
        private string $token,
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function pushOrder(OrderDataDto $order): PushResultDto
    {
        $body = $this->streamFactory->createStream(json_encode([
            'orderId' => $order->orderId,
            'warehouse' => $order->warehouse,
            'items' => $order->items,
        ], JSON_THROW_ON_ERROR));

        $request = $this->requestFactory
            ->createRequest('POST', rtrim($this->baseUrl, '/') . '/orders')
            ->withHeader('Authorization', "Bearer {$this->token}")
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $response = $this->client->sendRequest($request);
        $payload = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return new PushResultDto(
            orderId: $order->orderId,
            externalOrderId: (string) ($payload['externalOrderId'] ?? ''),
            success: $response->getStatusCode() < 300,
            message: $payload['message'] ?? null,
        );
    }

    public function getOrderStatus(string $externalOrderId): string
    {
        $request = $this->requestFactory
            ->createRequest('GET', rtrim($this->baseUrl, '/') . "/orders/{$externalOrderId}/status")
            ->withHeader('Authorization', "Bearer {$this->token}")
            ->withHeader('Accept', 'application/json');

        $payload = json_decode((string) $this->client->sendRequest($request)->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return (string) ($payload['status'] ?? 'unknown');
    }
}
