<?php

declare(strict_types=1);

namespace Pim\Application\Command\CatalogEdit;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public string $name,
        public ?string $parentName,
    ) {
    }
}
