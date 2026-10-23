<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Catalog\GetAll;

use Ozon\Application\Query\Catalog\CatalogResponse;
use Ozon\Domain\Entity\Catalog;
use Ozon\Domain\Repository\CatalogInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private CatalogInterface $catalogRepository
    ) {
    }

    /**
     * @param Query $query
     * @return CatalogResponse[]
     */
    public function __invoke(Query $query): array
    {
        return array_map(
            static fn (Catalog $catalog): CatalogResponse => new CatalogResponse(
                id: (string)$catalog->getCatalogUuid(),
                name: $catalog->getTypeName(),
                treePath: $catalog->getTreePath()
            ),
            $this->catalogRepository->findAll()
        );
    }
}
