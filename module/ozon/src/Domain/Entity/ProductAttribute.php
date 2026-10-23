<?php

declare(strict_types=1);

namespace Ozon\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;

#[ORM\Entity]
#[ORM\Table('ozon_product_attribute')]
#[ORM\HasLifecycleCallbacks]
class ProductAttribute extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid $productAttributeUuid,
        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid $attributeUuid,

        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid $productUuid,

        /**
         *
         */
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid $userUuid,

        /**
         * Пользовательские значения (сохраняем как есть)
         *
         * @var string[]
         */
        #[ORM\Column(length: 4000)]
        private array         $value = [],

        /**
         *
         */
        #[ORM\Column(length: 4000, nullable: true)]
        public ?string        $hash = null,

        /**
         * Провалидированные и подготовленные значения для маркетплейсов
         *
         * @var array{array-key, mixed}|null $prepareValue
         */
        #[ORM\Column(length: 4000, nullable: true)]
        private ?array        $prepareValue = null,

        /**
         *
         */
        #[ORM\Column]
        private bool          $isDeleted = false,
    ) {
    }

    public function setValue(array $value): void
    {
        $this->value = $value;
    }

    public function setHash(?string $hash): void
    {
        $this->hash = $hash;
    }

    public function setPrepareValue(?array $prepareValue): void
    {
        $this->prepareValue = $prepareValue;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function getProductAttributeUuid(): Uuid
    {
        return $this->productAttributeUuid;
    }

    public function getAttributeUuid(): Uuid
    {
        return $this->attributeUuid;
    }

    public function getProductUuid(): Uuid
    {
        return $this->productUuid;
    }

    public function getUserUuid(): Uuid
    {
        return $this->userUuid;
    }

    public function getValue(): mixed
    {
        if (count($this->value) <= 1) {
            return $this->value[0];
        }
        return $this->value;
    }

    public function getValues(): array
    {
        return $this->value;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function getPrepareValue(): ?array
    {
        $this->prepareValue['id'] = $this->prepareValue['attribute_id'];
        unset($this->prepareValue['attribute_id']);
        return $this->prepareValue;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


}
