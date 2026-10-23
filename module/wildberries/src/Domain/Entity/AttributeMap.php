<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\AttributeMapCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_attribute_map')]
#[ORM\HasLifecycleCallbacks]
class AttributeMap extends AggregateRoot
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::GUID)]
        private string  $attributeMapId,
        #[ORM\Column(type: Types::GUID)]
        private string  $wbAttributeId,
        #[ORM\Column(type: Types::GUID)]
        private string  $pimAttributeId,
        #[ORM\Column(length: 1024)]
        private string  $wbName,
        #[ORM\Column(length: 64)]
        private string  $pimAlias,
        #[ORM\Column(length: 16, nullable: true)]
        private ?string $wbMeasure = null,
        #[ORM\Column(length: 16, nullable: true)]
        private ?string $pimMeasure = null
    ) {
        $this->apply(new AttributeMapCreated(
            attributeMapId: $this->attributeMapId,
            wbAttributeId: $this->wbAttributeId,
            pimAttributeId: $this->pimAttributeId,
            wbName: $this->wbName,
            pimAlias: $this->pimAlias,
            wbMeasure: $this->wbMeasure,
            pimMeasure: $this->pimMeasure
        ));
    }

    public function getAttributeMapId(): string
    {
        return $this->attributeMapId;
    }

    public function setAttributeMapId(string $attributeMapId): void
    {
        $this->attributeMapId = $attributeMapId;
    }

    public function getWbAttributeId(): string
    {
        return $this->wbAttributeId;
    }

    public function setWbAttributeId(string $wbAttributeId): void
    {
        $this->wbAttributeId = $wbAttributeId;
    }

    public function getPimAttributeId(): string
    {
        return $this->pimAttributeId;
    }

    public function setPimAttributeId(string $pimAttributeId): void
    {
        $this->pimAttributeId = $pimAttributeId;
    }

    public function getWbName(): string
    {
        return $this->wbName;
    }

    public function setWbName(string $wbName): void
    {
        $this->wbName = $wbName;
    }

    public function getPimAlias(): string
    {
        return $this->pimAlias;
    }

    public function setPimAlias(string $pimAlias): void
    {
        $this->pimAlias = $pimAlias;
    }

    public function getWbMeasure(): ?string
    {
        return $this->wbMeasure;
    }

    public function setWbMeasure(?string $wbMeasure): void
    {
        $this->wbMeasure = $wbMeasure;
    }

    public function getPimMeasure(): ?string
    {
        return $this->pimMeasure;
    }

    public function setPimMeasure(?string $pimMeasure): void
    {
        $this->pimMeasure = $pimMeasure;
    }

}
