<?php

declare(strict_types=1);

namespace Ozon\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;

#[ORM\Entity]
#[ORM\Table('ozon_attribute_bridge')]
class AttributeBridge extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid   $attributeUuid,
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid   $attributePimUuid,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string $handler = '',
        /**
         *
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function getAttributeId(): Uuid
    {
        return $this->attributeUuid;
    }

    public function getAttributePimId(): Uuid
    {
        return $this->attributePimUuid;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

}
