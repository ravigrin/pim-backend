<?php

namespace Wildberries\Application\Command\Price\Make;

use App\Http\V2\RequestDto\PriceDto;
use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * @param PriceDto[] $prices
     */
    public function __construct(
        public string $username,
        public array  $prices
    ) {
    }
}
