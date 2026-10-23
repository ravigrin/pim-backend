<?php

declare(strict_types=1);

namespace OneC\Infrastructure\Guzzle;

use OneC\Domain\Repository\BrandInterface;
use RuntimeException;

/**
 * Заглушка: выгрузка брендов в 1С ещё не реализована (см. TODO.md).
 * Методы объявлены, чтобы контракт Domain был выполнен; вызов приводит к явной ошибке,
 * а не к тихому некорректному поведению.
 */
final class BrandRepository implements BrandInterface
{
    public function isBrandUpload(string $brandId): bool
    {
        throw new RuntimeException('OneC brand upload check is not implemented yet.');
    }

    public function exportBrand(string $brandId, string $brandName, bool $isUpdate): void
    {
        throw new RuntimeException('OneC brand export is not implemented yet.');
    }
}
