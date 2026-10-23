<?php

declare(strict_types=1);

namespace Pim\Application\Query\Brand;

use Pim\Domain\Entity\Brand;

/** @psalm-suppress MissingConstructor */
final class BrandDto
{
    public string $brandId;

    public string $name;

    public string $code;

    public static function getDto(Brand $brand): self
    {
        $result = new self();
        $result->brandId = $brand->getBrandId();
        $result->name = $brand->getName();
        $result->code = $brand->getCode();

        return $result;
    }
}
