<?php

declare(strict_types=1);

namespace Pim\Application\Query\ProductLine;

use Pim\Domain\Entity\ProductLine;

/** @psalm-suppress MissingConstructor */
final class ProductLineDto
{
    public string $productLineId;

    public string $brandId;

    public string $name;

    public string $code;

    public static function getDto(ProductLine $productLine): self
    {
        $result = new self();
        $result->productLineId = $productLine->getProductLineId();
        $result->brandId = $productLine->getBrandId();
        $result->name = $productLine->getName();
        $result->code = $productLine->getCode();

        return $result;
    }
}
