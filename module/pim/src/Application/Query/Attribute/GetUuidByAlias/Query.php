<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute\GetUuidByAlias;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(public string $alias)
    {
    }
}
