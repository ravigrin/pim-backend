<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Suggest\GetByAttribute;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Application\Query\Suggest\SuggestDto;
use Wildberries\Domain\Repository\SuggestInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private SuggestInterface $suggestRepository
    ) {
    }


    /**
     * @param Query $query
     * @return SuggestDto|null
     */
    public function __invoke(Query $query): ?SuggestDto
    {
        $suggest = $this->suggestRepository->findOneBy(
            ['attributeId' => $query->attributeId],
        );

        if ($suggest) {
            return SuggestDto::getDto($suggest);
        }

        return null;
    }
}
