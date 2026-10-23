<?php

declare(strict_types=1);

namespace Pim\Application\Query\Unit;

use Pim\Domain\Entity\Unit;

/** @psalm-suppress MissingConstructor */
final class UnitDto
{
    public string $unitId;

    public string $name;

    public string $code;

    public static function getDto(Unit $unit): self
    {
        $result = new self();
        $result->unitId = $unit->getId();
        $result->name = $unit->getName();
        $result->code = $unit->getCode();

        return $result;
    }
}
