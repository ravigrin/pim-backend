<?php

declare(strict_types=1);

namespace Ozon\Application\Query\AttributeGroup\GetAll;

use Ozon\Domain\Repository\AttributeGroupInterface;
use Shared\Domain\Query\QueryHandlerInterface;
use Shared\Domain\ValueObject\Uuid;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AttributeGroupInterface $attributeGroupRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Query $query): array
    {
        $catalogId = new Uuid($query->catalogId);
        return $this->attributeGroupRepository->findAttributeGroup(
            catalogId: $catalogId
        );
    }
}
