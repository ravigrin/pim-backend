<?php

declare(strict_types=1);

namespace OneC\Infrastructure\Guzzle\Resource;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

final readonly class HttpClient
{
    public function __construct(
        public ClientInterface $client,
    ) {
    }

    /**
     * @return array<string, mixed>
     * @throws ClientExceptionInterface
     */
    public function request(RequestInterface $request): array
    {
        $response = $this->client->sendRequest($request);

        /** @var array<string, mixed> $content */
        $content = json_decode(
            json: $response->getBody()->getContents(),
            associative: true
        );
        return $content;
    }
}
