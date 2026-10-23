<?php

declare(strict_types=1);

namespace Wildberries\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;
use Wildberries\Domain\Event\MediaCreated;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'wb_media')]
#[ORM\HasLifecycleCallbacks]
class Media extends AggregateRoot
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private string  $mediaId,
        #[ORM\Column]
        private int     $number,
        #[ORM\Column(type: 'uuid')]
        private string  $productId,
        #[ORM\Column(length: 256, nullable: true)]
        private ?string $little = null,
        #[ORM\Column(length: 256, nullable: true)]
        private ?string $small = null,
        #[ORM\Column(length: 256, nullable: true)]
        private ?string $big = null,
        #[ORM\Column(length: 256, nullable: true)]
        private ?string $video = null,
        #[ORM\Column(length: 256)]
        private ?string $hash = null
    )
    {
        $this->apply(new MediaCreated(
            mediaId: $mediaId,
            number: $number,
            productId: $productId,
            little: $little,
            small: $small,
            big: $big,
            video: $video,
            hash: $hash
        ));
    }

    /**
     * Gets triggered only on insert
     */
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime("now");
    }

    /**
     * Gets triggered every time on update
     */
    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime("now");
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getMediaId(): string
    {
        return $this->mediaId;
    }

    public function setMediaId(string $mediaId): void
    {
        $this->mediaId = $mediaId;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    public function getLittle(): ?string
    {
        return $this->little;
    }

    public function setLittle(?string $little): void
    {
        $this->little = $little;
    }

    public function getSmall(): ?string
    {
        return $this->small;
    }

    public function setSmall(?string $small): void
    {
        $this->small = $small;
    }

    public function getBig(): ?string
    {
        return $this->big;
    }

    public function setBig(?string $big): void
    {
        $this->big = $big;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): void
    {
        $this->video = $video;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): void
    {
        $this->hash = $hash;
    }
}
