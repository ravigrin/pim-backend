<?php

declare(strict_types=1);

namespace Pim\Application\Query\ProductLine\GetAll;

use Pim\Application\Query\ProductLine\ProductLineDto;
use Pim\Domain\Entity\User;
use Pim\Domain\Repository\Pim\ProductLineInterface;
use Pim\Domain\Repository\Pim\UserInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductLineInterface $productLineRepository,
        private UserInterface        $userRepository
    ) {
    }

    /**
     * @return ProductLineDto[]
     *
     */
    public function __invoke(Query $query): array
    {
        $user = $this->userRepository->findByUsername($query->username);

        $result = [];
        if ($user instanceof User) {

            $criteria = ['brandId' => $query->brandId];

            if ($user->getProductLines() != []) {
                $criteria['productLineId'] = $user->getProductLines();
            }
            $result = $this->productLineRepository->findByCriteria($criteria);
        }

        return array_map(ProductLineDto::getDto(...), $result);
    }
}
