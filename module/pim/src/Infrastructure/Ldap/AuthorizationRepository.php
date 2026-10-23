<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Ldap;

use Pim\Domain\Repository\Pim\AuthorizationInterface;

final class AuthorizationRepository implements AuthorizationInterface
{
    private const DEFAULT_URL = "ldaps://ldap.example.internal";
    private const DEFAULT_PORT = 636;
    private const DEFAULT_DOMAIN = '@example.internal';

    public function __construct(
        private readonly string $url = self::DEFAULT_URL,
        private readonly int $port = self::DEFAULT_PORT,
        private readonly string $domain = self::DEFAULT_DOMAIN,
    ) {
    }

    public function login(string $username, string $password): bool
    {
        $ldap = ldap_connect($this->url, $this->port);

        if ($ldap) {
            $bind = ldap_bind($ldap, $username . $this->domain, $password);
            if ($bind) {
                return true;
            }
        }

        return false;
    }
}
