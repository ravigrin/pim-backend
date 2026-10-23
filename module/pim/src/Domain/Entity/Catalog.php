<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('pim_catalog')]
class Catalog extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(length: 40, unique: true)]
        private string  $catalogId,
        /**
         *
         */
        #[ORM\Column(length: 255)]
        private string  $name,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string $treePath,
        /**
         *
         */
        #[ORM\Column(length: 40, nullable: true)]
        private ?string $parentId = null,
        /**
         *
         */
        #[ORM\Column(nullable: true)]
        private ?int    $level = null,
        /**
         *
         */
        #[ORM\Column]
        private bool    $isDeleted = false,
    ) {
    }

    public function getCatalogId(): string
    {
        return $this->catalogId;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTreePath(): string
    {
        return $this->treePath;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


}
