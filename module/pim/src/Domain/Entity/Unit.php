<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

/**
 * Отдажениие структуры компании. Работает два юнита (команды), которые занимаются
 * разработкой товаров
 */
#[ORM\Entity]
#[ORM\Table('pim_unit')]
#[ORM\HasLifecycleCallbacks]
class Unit extends AggregateRoot
{
    public function __construct(
        /**
         * Уникальный идентификатор юнита
         */
        #[ORM\Id]
        #[ORM\Column(length: 40, unique: true)]
        private readonly string $unitId,
        /**
         * Название юнита
         */
        #[ORM\Column(length: 1024)]
        private string          $name,
        /**
         * Код юнита, нужен для созналия артикула (sku)
         */
        #[ORM\Column(length: 1024)]
        private string          $code,
        /**
         * Технический статус
         */
        #[ORM\Column]
        private bool            $isDeleted = false,
    ) {
    }

    public function getId(): string
    {
        return $this->unitId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

}
