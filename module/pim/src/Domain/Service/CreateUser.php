<?php

namespace Pim\Domain\Service;

use Pim\Domain\Entity\User;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Service\EntityStoreService;

final readonly class CreateUser
{
    /**
     * @var string
     */
    private const CHARACTERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var int
     */
    private const LENGTH = 120;

    public function __construct(
        private EntityStoreService $entityStoreService
    ) {
    }

    /**
     * @param string[] $roles
     */
    public function create(string $username, array $roles = ['ROLE_USER']): User
    {
        $user = new User(
            userId: Uuid::uuid7()->toString(),
            username: $username,
            roles: $roles,
            token: $this->buildToken()
        );
        $this->entityStoreService->commit($user);

        return $user;
    }

    private function buildToken(): string
    {
        $charactersLength = strlen(self::CHARACTERS);
        $randomString = '';
        for ($i = 0; $i < self::LENGTH; ++$i) {
            $randomString .= self::CHARACTERS[random_int(0, $charactersLength - 1)];
        }

        return hash('sha3-256', $randomString);
    }
}
