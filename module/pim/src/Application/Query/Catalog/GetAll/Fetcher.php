<?php

declare(strict_types=1);

namespace Pim\Application\Query\Catalog\GetAll;

use Pim\Application\Query\Catalog\CatalogResponse;
use Pim\Domain\Entity\Catalog;
use Pim\Domain\Repository\Pim\CatalogInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private CatalogInterface $catalogRepository
    ) {
    }

    /**
     * @return CatalogResponse[]
     */
    public function __invoke(Query $query): array
    {
        return array_map(
            static fn (Catalog $catalog): CatalogResponse => new CatalogResponse(
                id: $catalog->getCatalogId(),
                name: $catalog->getName(),
                treePath: $catalog->getTreePath()
            ),
            $this->catalogRepository->findAll()
        );
    }
}
