<?php

declare(strict_types=1);

namespace Pim\Application\Query\Dictionary;

use Pim\Domain\Entity\Dictionary;

final class DictionaryDto
{
    public function __construct(
        public string  $id,
        public string  $value,
    ) {
    }

    public static function getDto(Dictionary $dictionary): self
    {
        return new self(
            id: $dictionary->getDictionaryId(),
            value: $dictionary->getValue(),
        );
    }
}
