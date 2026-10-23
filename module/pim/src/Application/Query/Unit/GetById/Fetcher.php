<?php

declare(strict_types=1);

namespace Pim\Application\Query\Unit\GetById;

use Pim\Application\Query\Unit\UnitDto;
use Pim\Domain\Entity\Unit;
use Pim\Domain\Repository\Pim\UnitInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(private UnitInterface $unitRepository)
    {
    }

    public function __invoke(Query $query): ?UnitDto
    {
        $unit = $this->unitRepository->findOneByCriteria([
            'unitId' => $query->unitId
        ]);
        return ($unit instanceof Unit) ? UnitDto::getDto($unit) : null;
    }
}
