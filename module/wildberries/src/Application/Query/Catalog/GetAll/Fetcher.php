<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Catalog\GetAll;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Application\Query\Catalog\CatalogResponse;
use Wildberries\Domain\Entity\Catalog;
use Wildberries\Domain\Repository\CatalogInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private CatalogInterface $catalogRepository
    ) {
    }

    /**
     * @return CatalogResponse[]
     * @throws \Exception
     */
    public function __invoke(Query $query): array
    {
        return array_map(
            static fn (Catalog $catalog): CatalogResponse => new CatalogResponse(
                id: $catalog->getCatalogId(),
                name: $catalog->getName(),
                treePath: 'Красота / '. $catalog->getName()
            ),
            $this->catalogRepository->findBy([])
        );
    }
}
