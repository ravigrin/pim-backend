<?php

declare(strict_types=1);

namespace Ozon\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;

#[ORM\Entity]
#[ORM\Table('ozon_product_attribute_history')]
#[ORM\HasLifecycleCallbacks]
class ProductAttributeHistory extends AggregateRoot
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $dateCreate;

    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid   $productAttributeHistoryUuid,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid   $productAttributeUuid,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid   $productUuid,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid   $attributeUuid,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid   $userUuid,

        /**
         * @var string[]|int[]|float[]
         */
        #[ORM\Column(length: 4000)]
        private readonly array  $newValue = [],

        /**
         * @var string[]|int[]|float[]|null
         */
        #[ORM\Column(length: 4000, nullable: true)]
        private readonly ?array $oldValue = null,
    ) {
    }

    #[ORM\PrePersist]
    public function setDateCreate(): void
    {
        $this->dateCreate = new \DateTime();
    }

    public function getDateCreate(): \DateTime
    {
        return $this->dateCreate;
    }

    public function getProductAttributeHistoryUuid(): Uuid
    {
        return $this->productAttributeHistoryUuid;
    }

    public function getProductAttributeUuid(): Uuid
    {
        return $this->productAttributeUuid;
    }

    public function getProductUuid(): Uuid
    {
        return $this->productUuid;
    }

    public function getAttributeUuid(): Uuid
    {
        return $this->attributeUuid;
    }

    public function getUserUuid(): Uuid
    {
        return $this->userUuid;
    }

    public function getNewValue(): array
    {
        return $this->newValue;
    }

    public function getOldValue(): ?array
    {
        return $this->oldValue;
    }

}
