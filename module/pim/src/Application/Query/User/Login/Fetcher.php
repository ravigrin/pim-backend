<?php

declare(strict_types=1);

namespace Pim\Application\Query\User\Login;

use Exception;
use Pim\Domain\Repository\Pim\AuthorizationInterface;
use Pim\Domain\Repository\Pim\UserInterface;
use Pim\Domain\Service\CreateUser;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private AuthorizationInterface $authUserRepository,
        private UserInterface          $userRepository,
        private CreateUser             $createUserService,
    ) {
    }

    /**
     * @return array{token: string, roles: string[], username: string, sources: string[]}|null
     * @throws Exception
     */
    public function __invoke(Query $query): ?array
    {
        $username = $query->username;
        $password = $query->password;

        // Проверяем учётные данные в каталоге (LDAP) до выдачи токена.
        if (!$this->authUserRepository->login(username: $username, password: $password)) {
            return null;
        }

        $user = $this->userRepository->findByUsername($username);
        if (!$user) {
            $user = $this->createUserService->create(username: $username);
        }

        return [
            'token' => $user->getToken(),
            'roles' => $user->getRoles(),
            'username' => $user->getUsername(),
            'sources' => $user->getSources(),
        ];
    }
}
