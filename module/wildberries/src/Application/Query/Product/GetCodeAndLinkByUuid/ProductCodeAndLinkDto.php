<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product\GetCodeAndLinkByUuid;

/** @psalm-suppress MissingConstructor */
final class ProductCodeAndLinkDto
{
    public function __construct(
        public ?string $vendorCode = null,
        public ?string $publicLink = null
    )
    {
    }
}
