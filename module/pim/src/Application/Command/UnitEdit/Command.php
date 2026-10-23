<?php

declare(strict_types=1);

namespace Pim\Application\Command\UnitEdit;

use Shared\Domain\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Command implements CommandInterface
{
    public function __construct(
        #[Assert\AtLeastOneOf([
            new Assert\Uuid(),
            new Assert\IsNull(),
        ])]
        public string $unitId,
        #[Assert\NotBlank]
        public string  $name,
        #[Assert\NotBlank]
        public string  $code,
    ) {
    }
}
