<?php

declare(strict_types=1);

namespace Pim\Application\Query\AttributeHistory\GetByProduct;

use Shared\Domain\Query\QueryInterface;

final readonly class Query implements QueryInterface
{
    public function __construct(
        public string $productId,
        public int $offset,
        public int $limit,
        public int $page
    ) {
    }
}
