<?php

declare(strict_types=1);

namespace Ozon\Application\Command\ProductPriceEdit;

use App\Http\V2\RequestDto\PriceDto;
use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * @param PriceDto[] $prices
     */
    public function __construct(
        public string $username,
        public array  $prices,
    ) {
    }
}
