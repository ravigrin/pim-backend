<?php

declare(strict_types=1);

namespace OneC\Domain\Repository;

interface BrandInterface
{
    public function isBrandUpload(string $brandId): bool;

    public function exportBrand(string $brandId, string $brandName, bool $isUpdate): void;

}
