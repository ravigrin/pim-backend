<?php

declare(strict_types=1);

namespace Pim\Application\Query\AttributeGroup\GetAll;

use Pim\Domain\Repository\Pim\AttributeInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AttributeInterface $attributeRepository,
    ) {
    }

    public function __invoke(Query $query): array
    {
        return $this->attributeRepository->findAttributeGroup();
    }
}
