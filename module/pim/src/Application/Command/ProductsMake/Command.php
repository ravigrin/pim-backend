<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductsMake;

use Pim\Domain\Entity\User;
use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * @param User $user
     * @param string $name
     * @param int $unit
     * @param string $brand
     * @param bool $isKit
     * @param string|null $productLine
     * @param string|null $status
     * @param string|null $vendorCode
     */
    public function __construct(
        public User    $user,
        public string  $name,
        public int     $unit,
        public string  $brand,
        public bool    $isKit,
        public ?string $productLine = null,
        public ?string $status = null,
        public ?string $vendorCode = null
    ) {
    }
}
