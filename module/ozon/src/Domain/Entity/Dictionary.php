<?php

declare(strict_types=1);

namespace Ozon\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;

#[ORM\Entity]
#[ORM\Table('ozon_dictionary')]
class Dictionary extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid    $dictionaryUuid,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private Uuid  $catalogUuid,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private Uuid  $attributeUuid,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string  $value,
        /**
         *
         */
        #[ORM\Column]
        private int     $catalogId,
        /**
         *
         */
        #[ORM\Column]
        private int     $attributeId,
        /**
         *
         */
        #[ORM\Column]
        private int     $dictionaryId,
        /**
         *
         */
        #[ORM\Column(length: 1024, nullable: true)]
        private ?string $info = null,
        /**
         *
         */
        #[ORM\Column(length: 1024, nullable: true)]
        private ?string $picture = null,
        /**
         *
         */
        #[ORM\Column]
        private bool    $isActive = true,
        /**
         *
         */
        #[ORM\Column]
        private bool    $isDeleted = false
    ) {
    }

    public function getDictionaryUuid(): Uuid
    {
        return $this->dictionaryUuid;
    }

    public function getCatalogUuid(): Uuid
    {
        return $this->catalogUuid;
    }

    public function getAttributeUuid(): Uuid
    {
        return $this->attributeUuid;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCatalogId(): int
    {
        return $this->catalogId;
    }

    public function getAttributeId(): int
    {
        return $this->attributeId;
    }

    public function getDictionaryId(): int
    {
        return $this->dictionaryId;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
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
