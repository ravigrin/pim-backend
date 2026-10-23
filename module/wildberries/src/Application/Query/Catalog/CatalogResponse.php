<?php

namespace Wildberries\Application\Query\Catalog;

use Shared\Domain\Specification\QueryResponse\CatalogInterface;

readonly class CatalogResponse implements CatalogInterface
{
    public function __construct(
        private string $id,
        private string $name,
        private string $treePath,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTreePath(): string
    {
        return $this->treePath;
    }
}
