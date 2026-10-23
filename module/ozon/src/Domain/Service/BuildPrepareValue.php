<?php

namespace Ozon\Domain\Service;

use Ozon\Domain\Entity\Attribute;
use Ozon\Domain\Entity\Dictionary;
use Ozon\Domain\Repository\DictionaryInterface;
use Shared\Domain\ValueObject\Uuid;

readonly class BuildPrepareValue
{
    public function __construct(
        private DictionaryInterface $dictionaryRepository,
    ) {
    }

    /**
     * @param array $values
     * @return array|null
     */
    public function build(Uuid $catalogId, Attribute $attribute, array $values): ?array
    {
        if (is_null($attribute) || $attribute->getAlias() !== null) {
            return null;
        }

        $isDictionary = (bool)$attribute->getDictionaryId();

        $items = [];
        foreach ($values as $value) {
            $item = [];
            // костыль для брендов
            if ($attribute->getAttributeId() === 85) {
                $item["value"] = $value;
                $externalId = $this->getBrandId($value);
                if (is_int($externalId)) {
                    $item["dictionary_value_id"] = $externalId;
                }
                $items[] = $item;
                continue;
            };

            $dictionary = $this->dictionaryRepository->findByCatalogAttributeValue(
                attributeId: $attribute->getAttributeUuid(),
                catalogId: $catalogId,
                value: (string)$value
            );

            $item = [];
            if ($isDictionary && $dictionary instanceof Dictionary) {
                $item['value'] = $dictionary->getValue();
                if ($dictionary->getDictionaryId() > 0) {
                    $item['dictionary_value_id'] = $dictionary->getDictionaryId();
                }
                $items[] = $item;
            }
        }

        // {"attribute_id":11650,"complex_id":0,"values":[{"dictionary_value_id":0,"value":"1"}]}
        return [
            "attribute_id" => $attribute->getAttributeId(),
            "complex_id" => 0,
            "values" => $items
        ];
    }

    private function getBrandId(string $brandName): ?int
    {
        $brands = [
            'Demo Brand A' => 900000000,
            'Demo Brand B' => 900000000,
            'Demo Brand C' => 900000000,
            'Demo Brand D' => 900000000,
            'Demo Brand F' => 900000000,
            'Demo Brand G' => 900000000,
            'Demo Brand H' => 900000000,
        ];
        return $brands[$brandName] ?? null;
    }

}
