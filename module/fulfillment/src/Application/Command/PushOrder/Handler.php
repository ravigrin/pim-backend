<?php

declare(strict_types=1);

namespace Fulfillment\Application\Command\PushOrder;

use App\Exception\NotFoundException;
use Fulfillment\Domain\Repository\Dto\OrderDataDto;
use Fulfillment\Domain\Repository\Dto\PushResultDto;
use Fulfillment\Domain\Repository\FulfillmentProviderRegistry;
use Shared\Domain\Command\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(private FulfillmentProviderRegistry $registry) {}

    public function __invoke(Command $command): PushResultDto
    {
        $provider = $this->registry->get($command->provider);

        return $provider->pushOrder(new OrderDataDto(
            orderId: $command->orderId,
            warehouse: $command->warehouse,
            items: $command->items,
        ));
    }
}
