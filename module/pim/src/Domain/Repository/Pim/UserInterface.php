<?php

declare(strict_types=1);

namespace Pim\Domain\Repository\Pim;

use Pim\Domain\Entity\User;

interface UserInterface
{
    /** @return User[] */
    public function findAll(): array;

    public function findById(string $userId): ?User;

    public function findByUsername(string $username): ?User;

}
