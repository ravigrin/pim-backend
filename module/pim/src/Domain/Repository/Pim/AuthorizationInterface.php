<?php

declare(strict_types=1);

namespace Pim\Domain\Repository\Pim;

interface AuthorizationInterface
{
    public function login(string $username, string $password): bool;
}
