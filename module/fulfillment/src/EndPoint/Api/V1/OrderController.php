<?php

declare(strict_types=1);

namespace Fulfillment\EndPoint\Api\V1;

use Fulfillment\Application\Command\PushOrder\Command;
use Fulfillment\Domain\Repository\Dto\PushResultDto;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    public function __construct(private readonly CommandBusInterface $commandBus) {}

    #[Route('/orders', name: 'fulfillment_push_order', methods: 'POST')]
    public function push(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        /** @var PushResultDto $result */
        $result = $this->commandBus->dispatch(new Command(
            provider: (string) $body['provider'],
            orderId: (string) $body['orderId'],
            warehouse: (string) $body['warehouse'],
            items: $body['items'] ?? [],
        ));

        return $this->json([
            'orderId' => $result->orderId,
            'externalOrderId' => $result->externalOrderId,
            'success' => $result->success,
            'message' => $result->message,
        ]);
    }
}
