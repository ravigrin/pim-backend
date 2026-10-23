<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

/**
 *
 */
#[ORM\Entity]
#[ORM\Table('pim_product_attribute_history')]
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
        #[ORM\Column(length: 40, unique: true)]
        private string $productAttributeHistoryId,
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string $productAttributeId,
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string $productId,
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string $attributeId,
        /**
         *
         */
        #[ORM\Column(length: 40)]
        private string $userId,

        /**
         * @var string[]
         */
        #[ORM\Column(length: 4000)]
        private array  $newValue = [],

        /**
         * @var string[]|null
         */
        #[ORM\Column(length: 4000, nullable: true)]
        private ?array $oldValue = null,
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

    public function getProductAttributeHistoryId(): string
    {
        return $this->productAttributeHistoryId;
    }

    public function getProductAttributeId(): string
    {
        return $this->productAttributeId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getAttributeId(): string
    {
        return $this->attributeId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getNewValue(): array|string
    {
        if (is_array($this->newValue) && count($this->newValue) === 1) {
            return $this->newValue[0];
        }
        return $this->newValue;
    }

    public function getOldValue(): array|string|null
    {
        if (is_array($this->oldValue) && count($this->oldValue) === 1) {
            return $this->oldValue[0];
        }
        return $this->oldValue;
    }


}
