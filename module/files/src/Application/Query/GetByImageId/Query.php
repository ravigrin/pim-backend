<?php

declare(strict_types=1);

namespace Files\Application\Query\GetByImageId;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    /**
     * @param string[] $imagesId
     */
    public function __construct(
        public array $imagesId
    ) {
    }
}
