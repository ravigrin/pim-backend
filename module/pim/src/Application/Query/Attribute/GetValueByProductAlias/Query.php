<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute\GetValueByProductAlias;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public string $productId,
        public string $alias
    ) {
    }
}
