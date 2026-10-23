<?php

declare(strict_types=1);

namespace Fulfillment\Domain\Repository;

use Fulfillment\Domain\Repository\Dto\OrderDataDto;
use Fulfillment\Domain\Repository\Dto\PushResultDto;

/**
 * Порт адаптера фулфилмент-оператора.
 *
 * Каждый оператор (FBO/FBS/свой склад) подключается реализацией этого
 * интерфейса в Infrastructure — домен и приложение не знают о вендоре.
 */
interface FulfillmentProviderInterface
{
    public function name(): string;

    /** Передача заказа на сборку/отгрузку. */
    public function pushOrder(OrderDataDto $order): PushResultDto;

    /** Статус заказа у оператора. */
    public function getOrderStatus(string $externalOrderId): string;
}
