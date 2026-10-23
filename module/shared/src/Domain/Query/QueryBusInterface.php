<?php

declare(strict_types=1);

namespace Shared\Domain\Query;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $query): mixed;
}
