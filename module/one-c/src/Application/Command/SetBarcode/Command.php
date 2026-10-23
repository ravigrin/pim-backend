<?php

declare(strict_types=1);

namespace OneC\Application\Command\SetBarcode;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * Установить barcode можно только после создания номенклатуры
     */
    public function __construct(
        public string $nomenclatureId,
        public string $barcode,
    ) {
    }
}
