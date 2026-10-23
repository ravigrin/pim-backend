<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Pim\Domain\Event\BrandPushed;

#[ORM\Entity]
#[ORM\Table('pim_brand')]
class Brand extends AggregateRoot
{
    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column(length: 40, unique: true)]
        private string $brandId,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string $name,
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string $code,
        /**
         *
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function pushToOneC(): void
    {
        $this->apply(new BrandPushed(
            brandId: $this->brandId,
            brandName: $this->name
        ));
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBrandId(): string
    {
        return $this->brandId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


}
