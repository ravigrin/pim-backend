<?php

declare(strict_types=1);

namespace Pim\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Shared\Domain\ValueObject\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table('pim_user')]
#[ORM\HasLifecycleCallbacks]
class User extends AggregateRoot implements UserInterface
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $dateCreate;

    public function __construct(
        /**
         *
         */
        #[ORM\Id]
        #[ORM\Column]
        private string   $userId,
        /**
         *
         */
        #[ORM\Column(unique: true)]
        private string $username,
        /**
         * @var string[]
         */
        #[ORM\Column(length: 4000)]
        private array  $roles = [],
        /**
         * @var string[]
         */
        #[ORM\Column(length: 4000)]
        private array  $units = [],
        /**
         * @var string[]
         */
        #[ORM\Column(length: 4000)]
        private array  $brands = [],
        /**
         * @var string[]
         */
        #[ORM\Column(length: 4000)]
        private array  $productLines = [],
        /**
         * @var string[]
         */
        #[ORM\Column(length: 4000)]
        private array  $sources = [],
        /**
         *
         */
        #[ORM\Column(length: 1024)]
        private string $token = "",
        /**
         *
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param string[] $units
     */
    public function setUnits(array $units): void
    {
        $this->units = $units;
    }

    /**
     * @param string[] $brands
     */
    public function setBrands(array $brands): void
    {
        $this->brands = $brands;
    }

    /**
     * @param string[] $productLines
     */
    public function setProductLines(array $productLines): void
    {
        $this->productLines = $productLines;
    }

    /**
     * @param string[] $sources
     */
    public function setSources(array $sources): void
    {
        $this->sources = $sources;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string[]
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * @return string[]
     */
    public function getUnits(): array
    {
        return $this->units;
    }

    /**
     * @return string[]
     */
    public function getBrands(): array
    {
        return $this->brands;
    }

    /**
     * @return string[]
     */
    public function getProductLines(): array
    {
        return $this->productLines;
    }

    public function eraseCredentials(): void
    {
        // Пароль не хранится в сущности (аутентификация через LDAP), очищать нечего.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
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
