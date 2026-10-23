<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Attribute\Group\Make;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * @param null|int[] $attributes
     */
    public function __construct(
        public string  $name,
        public ?string $type = null,
        public ?int    $order = null,
        public ?string $alias = null,
        public ?array  $attributes = null,
        public ?string $groupId = null,
        public ?string $tabId = null
    ) {
    }
}
