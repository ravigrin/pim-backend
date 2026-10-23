<?php

declare(strict_types=1);

namespace OneC\Application\Command\CreateBrand;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public string $brandId,
        public string $brandName,
    ) {
    }
}
