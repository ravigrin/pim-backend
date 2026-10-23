<?php

namespace Ozon\Domain\Repository\Internal;

use Shared\Domain\ValueObject\Uuid;

interface PimModuleInterface
{
    public function setPimStatusMarketplace(Uuid $productId): void;

    /**
     * @param string $productId
     * @return array{attributeId: string, value: string}
     */
    public function findAttributeByProduct(string $productId): array;

    /**
     * @param string[] $aliases
     * @return array<string, string>
     */
    public function findAttributeByAlias(array $aliases): array;

    public function findBrandValueByProduct(string $productId): ?string;

    /**
     * @return string[]
     */
    public function findBrandValues(): array;

    public function findUuidByVendorCode(string $vendorCode): ?string;

    public function findVendorCodeByUuid(string $uuid): ?string;

}
