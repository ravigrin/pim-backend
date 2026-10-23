<?php

declare(strict_types=1);

namespace AdAnalytics\EndPoint\Api\V1;

use AdAnalytics\Application\Query\Statistics\Query;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class StatisticsController extends AbstractController
{
    public function __construct(private readonly QueryBusInterface $queryBus) {}

    #[Route('/statistics', name: 'ad_analytics_statistics', methods: 'GET')]
    public function statistics(Request $request): JsonResponse
    {
        /** @var list<array<string, mixed>> $items */
        $items = $this->queryBus->dispatch(new Query(
            source: (string) $request->query->get('source'),
            dateFrom: (string) $request->query->get('from'),
            dateTo: (string) $request->query->get('to'),
        ));

        return $this->json($items);
    }
}
