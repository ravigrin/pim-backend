<?php

declare(strict_types=1);

namespace Pim\Application\Command\UserEdit;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * @param string[] $roles
     * @param string[] $units
     * @param string[] $brands
     * @param string[] $productLines
     * @param string[] $sources
     */
    public function __construct(
        public string $userId,
        public array $roles,
        public array $units,
        public array $brands,
        public array $productLines,
        public array $sources,
    ) {
    }
}
