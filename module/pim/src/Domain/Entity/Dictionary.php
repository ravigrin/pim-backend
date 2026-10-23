<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('pim_dictionary')]
class Dictionary extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(length: 40, unique: true)]
        private string $dictionaryId,
        /**
         *
         */
        #[ORM\Column]
        private string $attributeId,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string $value,
        /**
         *
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function getDictionaryId(): string
    {
        return $this->dictionaryId;
    }

    public function getAttributeId(): string
    {
        return $this->attributeId;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


}
