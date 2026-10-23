<?php

namespace Shared\Domain\Specification\QueryResponse;

interface CatalogInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getTreePath(): string;

}

