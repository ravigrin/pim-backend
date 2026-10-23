<?php

declare(strict_types=1);

namespace OneC\Application\Command\CreateNomenclature;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * Создание номенклатуры в 1c. У нас это называется товаром
     * nomenclatureId - это productId
     *
     * @param string[] $products
     */
    public function __construct(
        public string  $nomenclatureId,
        public string  $brandId,
        public string  $nomenclatureName,
        public string  $brandName,
        public string  $vendorCode,
        public bool    $isKit,
        public array   $products,
        public ?string $barcode,
        public ?string $productLineName,
    ) {
    }
}
