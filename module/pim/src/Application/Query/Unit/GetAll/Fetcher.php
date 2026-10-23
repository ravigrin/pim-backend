<?php

declare(strict_types=1);

namespace Pim\Application\Query\Unit\GetAll;

use Pim\Application\Query\Unit\UnitDto;
use Pim\Domain\Repository\Pim\UnitInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private UnitInterface $unitRepository,
    ) {
    }

    /**
     * @return UnitDto[]
     */
    public function __invoke(Query $query): array
    {
        return array_map(
            UnitDto::getDto(...),
            $this->unitRepository->findByCriteria([])
        );
    }
}
