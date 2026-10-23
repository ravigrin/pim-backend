<?php

declare(strict_types=1);

namespace App\Http\V2;

use App\Exception\SourceNotFound;
use App\Factory\CatalogQueryFactory;
use App\Http\V2\ResponseDto\CatalogDto;
use Shared\Domain\Query\QueryBusInterface;
use Shared\Domain\Specification\QueryResponse\CatalogInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
final class CatalogsController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @throws SourceNotFound
     */
    #[Route('/catalogs', name: 'catalog_list', methods: 'post')]
    public function list(Request $request): JsonResponse
    {
        $query = CatalogQueryFactory::getQuery(
            source: $request->request->getString('source')
        );

        $catalogs = $this->queryBus->dispatch($query);

        return $this->json(
            array_map(
                fn (CatalogInterface $catalogResponse): CatalogDto => CatalogDto::getDto($catalogResponse),
                $catalogs
            )
        );
    }
}
