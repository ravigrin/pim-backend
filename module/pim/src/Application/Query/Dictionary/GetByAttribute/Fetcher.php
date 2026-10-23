<?php

declare(strict_types=1);

namespace Pim\Application\Query\Dictionary\GetByAttribute;

use Pim\Application\Query\Dictionary\DictionaryDto;
use Pim\Domain\Repository\Pim\DictionaryInterface;
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
            $this->dictionaryRepository->findByCriteria(
                ["attributeId" => $query->attributeId]
            )
        );
    }
}
