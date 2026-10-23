<?php

declare(strict_types=1);

namespace Ozon\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;

#[ORM\Entity]
#[ORM\Table('ozon_catalog')]
class Catalog extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid   $catalogUuid,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string $treePath,
        /**
         *
         */
        #[ORM\Column]
        private int    $level,
        /**
         *
         */
        #[ORM\Column]
        private int    $catalogId,
        /**
         *
         */
        #[ORM\Column]
        private int    $typeId,
        /**
         *
         */
        #[ORM\Column]
        private string $typeName,
        /**
         *
         */
        #[ORM\Column]
        private bool   $isActive = false,
        /**
         *
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function getCatalogUuid(): Uuid
    {
        return $this->catalogUuid;
    }

    public function getCatalogId(): int
    {
        return $this->catalogId;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function getTreePath(): string
    {
        return $this->treePath;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


}
