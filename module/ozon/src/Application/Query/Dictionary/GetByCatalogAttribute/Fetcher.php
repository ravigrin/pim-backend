<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Dictionary\GetByCatalogAttribute;

use Ozon\Application\Query\Dictionary\DictionaryDto;
use Ozon\Domain\Repository\DictionaryInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private DictionaryInterface $dictionaryRepository,
    ) {
    }

    /**
     * @return DictionaryDto[]
     */
    public function __invoke(Query $query): array
    {
        return array_map(
            DictionaryDto::getDto(...),
            $this->dictionaryRepository->findByAttributeAndCatalog(
                catalogId: $query->catalogId,
                attributeId: $query->attributeId,
            )
        );
    }
}
