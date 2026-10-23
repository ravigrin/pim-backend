<?php

declare(strict_types=1);

namespace Pim\Application\Query\ProductLine\GetById;

use Shared\Domain\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Query implements QueryInterface
{
    public function __construct(
        #[Assert\Sequentially([
            new Assert\Uuid(),
            new Assert\NotBlank(),
        ])]
        public string $productLineId
    )
    {
    }
}
