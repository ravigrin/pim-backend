<?php

declare(strict_types=1);

namespace Pim\Application\Query\User;

use Pim\Domain\Entity\User;

/** @psalm-suppress MissingConstructor */
final class UserDto
{
    public string $userId;

    public string $username;

    /**
     * @var string[]
     */
    public array $roles;

    /**
     * @var string[]
     */
    public array $sources;

    /**
     * @var string[]
     */
    public array $units;

    /**
     * @var string[]
     */
    public array $brands;

    /**
     * @var string[]
     */
    public array $productLines;

    public static function getDto(User $user): self
    {
        $result = new self();
        $result->userId = $user->getUserId();
        $result->username = $user->getUsername();
        $result->roles = $user->getRoles();
        $result->units = $user->getUnits();
        $result->brands = $user->getBrands();
        $result->productLines = $user->getProductLines();
        $result->sources = $user->getSources();
        return $result;
    }
}
