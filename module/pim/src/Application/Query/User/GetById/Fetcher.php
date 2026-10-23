<?php

declare(strict_types=1);

namespace Pim\Application\Query\User\GetById;

use Pim\Application\Query\User\UserDto;
use Pim\Domain\Entity\User;
use Pim\Domain\Repository\Pim\UserInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(private UserInterface $userRepository)
    {
    }

    public function __invoke(Query $query): ?UserDto
    {
        $user = $this->userRepository->findById($query->userId);
        return ($user instanceof User) ? UserDto::getDto($user) : null;
    }
}
