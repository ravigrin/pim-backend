<?php

declare(strict_types=1);

namespace Pim\Application\Query\Brand\GetByUser;

use Pim\Application\Query\Brand\BrandDto;
use Pim\Domain\Entity\User;
use Pim\Domain\Repository\Pim\BrandInterface;
use Pim\Domain\Repository\Pim\UserInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private BrandInterface $brandRepository,
        private UserInterface  $userRepository
    ) {
    }

    /**
     * @return BrandDto[]
     */
    public function __invoke(Query $query): array
    {
        $user = $this->userRepository->findByUsername($query->username);
        $brands = [];
        if ($user instanceof User) {
            $brands = $this->brandRepository->findByCriteria([]);
        }

        return array_map(BrandDto::getDto(...), $brands);
    }
}
