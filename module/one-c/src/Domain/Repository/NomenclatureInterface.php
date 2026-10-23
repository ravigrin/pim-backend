<?php

declare(strict_types=1);

namespace OneC\Domain\Repository;

interface NomenclatureInterface
{
    public function isNomenclatureUpload(string $guid): bool;

    public function pushNomenclature(
        string  $nomenclatureId,
        string  $brandId,
        string  $nomenclatureName,
        string  $brandName,
        string  $vendorCode,
        bool    $isKit,
        bool    $isUpdate,
        ?string $productLineName,
    ): void;

    /**
     * @param string[] $products
     */
    public function pushKit(
        string $nomenclatureId,
        array  $products,
    ): string;

    public function pushBarcode(string $nomenclatureId, string $barcode): string;

}
