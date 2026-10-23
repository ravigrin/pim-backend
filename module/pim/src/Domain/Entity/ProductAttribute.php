<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('pim_product_attribute')]
#[ORM\HasLifecycleCallbacks]
class ProductAttribute extends AggregateRoot
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $dateCreate;

    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(length: 40, unique: true)]
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
         *
         */
        #[ORM\Column(length: 4000)]
        private array  $value = [],
        /**
         *
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
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

    /**
     * @return string[]|string|null
     */
    public function getValue(): array|string|null
    {
        if (count($this->value) === 1) {
            return $this->value[0];
        }
        return $this->value;
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->value;
    }

    /**
     * @param string[] $value
     */
    public function setValue(array $value): void
    {
        $this->value = $value;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function getDateCreate(): \DateTime
    {
        return $this->dateCreate;
    }

    #[ORM\PrePersist]
    public function setDateCreate(): void
    {
        $this->dateCreate = new \DateTime();
    }


}
