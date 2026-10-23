<?php

declare(strict_types=1);

namespace App\Http\V2;

use App\Factory\AttributeGroupQueryFactory;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
final class AttributeGroupsController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
    ) {
    }

    /**
     * Получение списка всех активных групп атрибутов
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('attribute-group', name: 'attribute_group_list', methods: 'post')]
    public function list(Request $request): JsonResponse
    {
        $query = AttributeGroupQueryFactory::getQuery(
            source: $request->request->getString('source'),
            catalogId: $request->request->getString('catalogId'),
        );
        return $this->json($this->queryBus->dispatch($query));
    }
}
