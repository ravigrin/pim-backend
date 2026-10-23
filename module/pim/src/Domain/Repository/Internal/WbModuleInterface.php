<?php

namespace Pim\Domain\Repository\Internal;

interface WbModuleInterface
{
    /**
     * @param string $productId
     * @return int|null
     */
    public function getProductStatus(string $productId): ?int;

    public function pushProduct(string $productId): void;
}
