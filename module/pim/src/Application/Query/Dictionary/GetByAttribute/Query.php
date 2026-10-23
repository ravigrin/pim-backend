<?php

declare(strict_types=1);

namespace Pim\Application\Query\Dictionary\GetByAttribute;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public string $attributeId,
    ) {
    }
}
