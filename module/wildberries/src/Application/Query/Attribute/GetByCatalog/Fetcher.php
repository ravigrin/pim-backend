<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Attribute\GetByCatalog;

use Shared\Domain\Query\QueryHandlerInterface;
use Wildberries\Application\Query\Attribute\AttributeDto;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Repository\AttributeInterface;
use Exception;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AttributeInterface $attributeRepository
    ) {
    }

    /**
     * @return AttributeDto[]
     * @throws Exception
     */
    public function __invoke(Query $query): array
    {
        return match ($query->catalogId) {
            null, '' => array_map(
                fn (Attribute $attribute): AttributeDto => AttributeDto::getDto($attribute),
                $this->attributeRepository->findWithAlias()
            ),
            default => array_map(
                fn (Attribute $attribute): AttributeDto => AttributeDto::getDto($attribute),
                $this->attributeRepository->findByCatalog($query->catalogId)
            )
        };

    }
}
