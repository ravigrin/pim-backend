<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Attribute\Group\Delete;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public int $groupId
    ) {
    }
}
