<?php

declare(strict_types=1);

namespace Pim\Application\Query\Product\GetVendorCode;

use Pim\Application\Query\Brand\BrandDto;
use Pim\Application\Query\ProductLine\ProductLineDto;
use Pim\Application\Query\Unit\UnitDto;
use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public UnitDto         $unit,
        public BrandDto        $brand,
        public bool            $isKit,
        public ?ProductLineDto $productLine = null
    )
    {
    }
}
