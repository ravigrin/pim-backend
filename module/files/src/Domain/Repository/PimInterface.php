<?php

namespace Files\Domain\Repository;

interface PimInterface
{
    /**
     * @param string[] $imagesId
     */
    public function saveImages(string $productId, array $imagesId): void;
}
