<?php

declare(strict_types=1);

namespace Ozon\Application\Query\Dictionary;

use Ozon\Domain\Entity\Dictionary;

final class DictionaryDto
{
    public function __construct(
        public int    $id,
        public string $value,
        public string $picture = '',
        public string $info = '',
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
