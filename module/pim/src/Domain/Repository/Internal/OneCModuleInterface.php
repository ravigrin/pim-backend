<?php

namespace Pim\Domain\Repository\Internal;

interface OneCModuleInterface
{
    /**
     * @param string[] $products
     */
    public function pushProduct(
        string  $nomenclatureId,
        string  $brandId,
        string  $nomenclatureName,
        string  $brandName,
        string  $vendorCode,
        bool    $isKit,
        array   $products,
        ?string $barcode,
        ?string $productLineName,
    ): void;
}
