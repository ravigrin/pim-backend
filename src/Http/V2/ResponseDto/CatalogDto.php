<?php

declare(strict_types=1);

namespace App\Http\V2\ResponseDto;

use Shared\Domain\Specification\QueryResponse\CatalogInterface;

/** @psalm-suppress MissingConstructor */
final class CatalogDto
{
    public string $id;

    public string $name;

    public ?string $treePath = null;

    public static function getDto(CatalogInterface $catalog): self
    {
        $result = new self();
        $result->id = $catalog->getId();
        $result->name = $catalog->getName();
        $result->treePath = $catalog->getTreePath();

        return $result;
    }
}
