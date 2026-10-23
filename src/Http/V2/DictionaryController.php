<?php

declare(strict_types=1);

namespace App\Http\V2;

use App\Factory\DictionaryQueryFactory;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
final class DictionaryController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    #[Route('/dictionary', name: 'catalog_dictionary_list', methods: 'post')]
    public function dictionary(Request $request): JsonResponse
    {
        $query = DictionaryQueryFactory::getQuery(
            source: $request->request->getString('source'),
            attributeId: $request->request->getString('attributeId'),
            catalogId: $request->request->getString('catalogId'),
        );
        return $this->json($this->queryBus->dispatch($query));


    }
}
