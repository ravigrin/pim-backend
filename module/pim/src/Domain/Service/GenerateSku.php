<?php

declare(strict_types=1);

namespace Pim\Domain\Service;

use Pim\Application\Query\Brand\BrandDto;
use Pim\Application\Query\ProductLine\ProductLineDto;
use Pim\Application\Query\Unit\UnitDto;
use Pim\Domain\Repository\Pim\ProductInterface;

final readonly class GenerateSku
{
    public function __construct(
        private ProductInterface $productRepository
    ) {
    }

    public function build(UnitDto $unit, BrandDto $brand, ?ProductLineDto $productLine, bool $isKit): string
    {
        $increment = $this->productRepository->getIncrementBy(
            brandId: $brand->brandId,
            productLineId: $productLine?->productLineId,
            isKit: $isKit
        );

        $productLineCode = isset($productLine) ? $productLine->code : '0';
        if ($isKit) {
            $productLineCode = 'SET';
        }

        $unitId = sprintf('%02s', $unit->code);
        $brandCode = sprintf('%03s', $brand->code);
        $productLineCode = sprintf(
            '%03s',
            $productLineCode
        );
        $increment = sprintf('%04d', $increment);

        return $unitId . $brandCode . $productLineCode . $increment;
    }
}
