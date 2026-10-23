<?php

declare(strict_types=1);

namespace App\Http\V2;

use App\Exception\SourceNotFound;
use App\Factory\AttributeHistoryQueryFactory;
use App\Factory\AttributeQueryFactory;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
final class AttributesController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
    ) {
    }

    /**
     * @throws SourceNotFound
     */
    #[Route('/attributes', name: 'attributes', methods: 'post')]
    public function catalog(Request $request): JsonResponse
    {
        $query = AttributeQueryFactory::getQuery(
            source: $request->request->getString('source'),
            catalogId: $request->request->getString('catalogId')
        );
        return $this->json($this->queryBus->dispatch($query));
    }

    /**
     * @throws SourceNotFound
     */
    #[Route('/attributes/history', name: 'attributes_history', methods: 'post')]
    public function history(Request $request): JsonResponse
    {
        $query = AttributeHistoryQueryFactory::getQuery(
            source: $request->request->getString('source'),
            productId: $request->request->getString('productId'),
            page: $request->request->getInt('page'),
            perPage: $request->request->getInt('perPage'),
        );
        return $this->json($this->queryBus->dispatch($query));
    }
}
