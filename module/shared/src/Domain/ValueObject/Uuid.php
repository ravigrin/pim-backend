<?php

namespace Shared\Domain\ValueObject;

use Shared\Domain\Exception\ValueObjectException;

readonly class Uuid
{
    /**
     * @throws ValueObjectException
     */
    public static function build(): self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid7()->toString());
    }

    /**
     * @throws ValueObjectException
     */
    public static function fromString(string $uuid): Uuid
    {
        return new self($uuid);
    }

    /**
     * @throws ValueObjectException
     */
    public function __construct(private string $uuid)
    {
        $isValid = \Ramsey\Uuid\Uuid::isValid($uuid);
        if ($isValid === false) {
            throw new ValueObjectException(
                sprintf('Uuid: %s not valid', $uuid)
            );
        }
    }

    public function getValue(): string
    {
        return $this->uuid;
    }

    public function isEqual(Uuid $uuid): bool
    {
        return $this->uuid === $uuid->getValue();
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
