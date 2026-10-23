<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Shared\Domain\Exception\DatabaseValueException;
use Shared\Domain\Exception\ValueObjectException;
use Shared\Domain\ValueObject\Uuid;

class UuidType extends GuidType
{
    public const string TYPE = 'uuid';

    /**
     * @throws DatabaseValueException|ValueObjectException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        if (is_null($value)) {
            return null;
        }

        if (is_string($value)) {
            return new Uuid($value);
        }
        throw new DatabaseValueException(
            sprintf("UuidType: value %s is not string ", serialize($value))
        );
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::TYPE;
    }
}
