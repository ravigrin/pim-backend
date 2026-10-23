<?php

declare(strict_types=1);

namespace Pim\Application\Query\User\Login;

use Shared\Domain\Query\QueryInterface;

final readonly class Query implements QueryInterface
{
    public function __construct(
        public string $username,
        public string $password,
    ) {
    }
}
