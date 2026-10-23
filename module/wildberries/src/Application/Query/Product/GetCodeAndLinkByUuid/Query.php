<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product\GetCodeAndLinkByUuid;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public string $productId
    )
    {
    }
}
