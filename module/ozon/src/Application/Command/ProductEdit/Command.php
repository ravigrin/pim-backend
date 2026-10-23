<?php

declare(strict_types=1);

namespace Ozon\Application\Command\ProductEdit;

use Pim\Domain\Entity\User;
use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * @param User $user
     * @param string $catalogId
     * @param Attribute[] $attributes
     * @param string[] $union
     * @param string $productId
     */
    public function __construct(
        public User   $user,
        public string $catalogId,
        public array  $attributes,
        public array  $union,
        public string $productId,
        public string $vendorCode,
    ) {
    }
}
