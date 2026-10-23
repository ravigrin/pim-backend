<?php

declare(strict_types=1);

namespace Ozon\Application\Command\AttributeImport;

use Ozon\Domain\Entity\Attribute;
use Ozon\Domain\Repository\AttributeGroupInterface;
use Ozon\Domain\Repository\AttributeInterface;
use Ozon\Domain\Repository\AttributeTabInterface;
use Ozon\Domain\Repository\CatalogInterface;
use Ozon\Domain\Repository\Dwh\OzonImportInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Shared\Domain\ValueObject\Uuid;

final readonly class Handler implements CommandHandlerInterface
{
    /** @var string[] */
    public const array ASSOCIATIVE = [
        'String' => 'string',
        'Boolean' => 'bool',
        'Decimal' => 'float',
        'ImageURL' => 'string',
        'Integer' => 'integer',
        'multiline' => 'text',
        'URL' => 'string',
    ];

    public function __construct(
        private OzonImportInterface     $importRepository,
        private CatalogInterface        $catalogRepository,
        private AttributeInterface      $attributeRepository,
        private PersistenceInterface    $persistenceRepository,
        private AttributeTabInterface   $attributeTabRepository,
        private AttributeGroupInterface $attributeGroupRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Command $command): void
    {
        $tab = $this->attributeTabRepository->findByAlias("specifications");
        if (is_null($tab)) {
            throw new \Exception('AttributeImport: tab not found');
        }
        $group = $this->attributeGroupRepository->findByAlias("common");
        if (is_null($group)) {
            throw new \Exception('AttributeImport: group not found');
        }

        $catalogs = $this->catalogRepository->findAll();
        foreach ($catalogs as $catalog) {
            $attributes = $this->importRepository->findAttributesByTypeId($catalog->getTypeId());
            foreach ($attributes as $attribute) {
                $localAttribute = $this->attributeRepository->findOneByCriteria([
                    "typeId" => $attribute->typeId
                ]);

                if (is_null($localAttribute)) {
                    $localAttribute = new Attribute(
                        attributeUuid: Uuid::build(),
                        catalogUuid: $catalog->getCatalogUuid(),
                        catalogId: $attribute->categoryId,
                        typeId: $attribute->typeId,
                        attributeId: $attribute->attributeId,
                        attributeComplexId: $attribute->attributeComplexId,
                        name: $attribute->name,
                        description: $attribute->description,
                        pimType: self::ASSOCIATIVE[$attribute->type],
                        type: $attribute->type,
                        isCollection: $attribute->isCollection,
                        isRequired: $attribute->isRequired,
                        isAspect: $attribute->isAspect,
                        maxValueCount: $attribute->maxValueCount,
                        groupName: $attribute->groupName,
                        groupId: $attribute->groupId,
                        dictionaryId: $attribute->dictionaryId,
                        tabUuid: $tab->getAttributeTabId(),
                        groupUuid: $group->getAttributeGroupUuid(),
                    );
                    $this->persistenceRepository->persist($localAttribute);
                }
            }
            $this->persistenceRepository->flush();
        }
    }

}
