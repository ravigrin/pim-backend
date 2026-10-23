<?php

declare(strict_types=1);

namespace Pim\Application\Query\User\GetAll;

use Pim\Application\Query\User\UserDto;
use Pim\Domain\Repository\Pim\UserInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(private UserInterface $userRepository)
    {
    }

    /**
     * @return UserDto[]
     */
    public function __invoke(Query $query): array
    {
        $users = $this->userRepository->findAll();
        return array_map(UserDto::getDto(...), $users);
    }
}
