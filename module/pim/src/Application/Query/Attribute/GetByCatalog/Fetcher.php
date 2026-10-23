<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute\GetByCatalog;

use Pim\Application\Query\Attribute\AttributeDto;
use Pim\Domain\Entity\Attribute;
use Pim\Domain\Repository\Pim\AttributeInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AttributeInterface $attributeRepository,
    ) {
    }

    /**
     * @return AttributeDto[]
     * @throws \Exception
     */
    public function __invoke(Query $query): array
    {
        return array_map(
            fn (Attribute $attribute): AttributeDto => AttributeDto::getDto($attribute),
            $this->attributeRepository->findByCriteria([])
        );
    }
}
