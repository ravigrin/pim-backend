<?php

namespace Wildberries\Domain\Repository\Dwh\Dto;

final class WbProductCharacteristicsDto
{
    public function __construct(
        public string           $name,
        public int|string|array $value,
        public ?int             $id = null
    )
    {
    }

    /**
     * Формирует массив WbProductCharacteristicsDto из json строки
     * @return WbProductCharacteristicsDto[]
     * @throws \JsonException
     */
    public static function fromString(string $characteristics): array
    {
        $characteristics = json_decode($characteristics, true, 512, JSON_THROW_ON_ERROR);
        $response = [];
        if (is_array($characteristics)) {
            foreach ($characteristics as $char) {
                $response[] = new self(
                    name: $char['name'],
                    value: $char['value'],
                    id: $char['id'] ?? null
                );
            }
        }

        return $response;
    }
}
