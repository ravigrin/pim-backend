<?php

declare(strict_types=1);

namespace Pim\Application\Query\Attribute\GetByAliases;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    /**
     * @param string[] $aliases
     */
    public function __construct(public array $aliases)
    {
    }
}
