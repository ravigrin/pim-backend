<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Suggest;

use Wildberries\Domain\Entity\Suggest;

/** @psalm-suppress MissingConstructor */
final class SuggestDto
{
    public function __construct(
        public array   $value,
        public ?string $suggestId = null,
        public ?string $info = null
    ) {
    }

    public static function getDto(Suggest $suggest): self
    {
        return new self(
            value: $suggest->getValue(),
            suggestId: $suggest->getSuggestId(),
            info: $suggest->getInfo()
        );
    }
}
