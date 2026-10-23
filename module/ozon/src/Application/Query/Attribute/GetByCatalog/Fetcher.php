<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Attribute\GetByCatalog;

use Exception;
use Ozon\Application\Query\Attribute\AttributeDto;
use Ozon\Domain\Entity\Attribute;
use Ozon\Domain\Repository\AttributeInterface;
use Shared\Domain\Query\QueryHandlerInterface;
use Shared\Domain\ValueObject\Uuid;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AttributeInterface $attributeRepository,
    ) {
    }

    /**
     * @return AttributeDto[]
     * @throws Exception
     */
    public function __invoke(Query $query): array
    {
        $catalogId = null;
        if ($query->catalogId) {
            $catalogId = Uuid::fromString($query->catalogId);
        }
        return array_map(
            fn (Attribute $attribute): AttributeDto => AttributeDto::getDto($attribute),
            $this->attributeRepository->findByCatalog(catalogId: $catalogId)
        );
    }
}
