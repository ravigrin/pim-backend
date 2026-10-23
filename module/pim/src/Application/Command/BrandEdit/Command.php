<?php

declare(strict_types=1);

namespace Pim\Application\Command\BrandEdit;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public ?string $brandId,
        public string $name,
        public string $code,
    ) {
    }
}
