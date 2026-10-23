<?php

namespace Pim\Domain\Repository\Internal;

interface OzonModuleInterface
{
    public function getOzonStatus(string $productId);

    public function pushProduct(string $productId): void;

}