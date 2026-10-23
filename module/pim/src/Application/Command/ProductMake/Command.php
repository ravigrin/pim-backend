<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductMake;

use Pim\Application\Query\Brand\BrandDto;
use Pim\Application\Query\ProductLine\ProductLineDto;
use Pim\Application\Query\Unit\UnitDto;
use Pim\Domain\Entity\User;
use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * @param string[] $union
     */
    public function __construct(
        public User            $user,
        public array           $attributes,
        public string          $productId,
        public ?UnitDto        $unit,
        public ?BrandDto       $brand,
        public ?ProductLineDto $productLine,
        public ?string         $catalogId,
        public array           $union,
        public bool            $isKit,
        public string          $vendorCode
    ) {
    }
}
