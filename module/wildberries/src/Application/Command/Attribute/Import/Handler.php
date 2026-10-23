<?php

namespace Wildberries\Application\Command\Attribute\Import;

use Exception;
use Ramsey\Uuid\Uuid as UuidBuild;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Entity\Catalog;
use Wildberries\Domain\Entity\CatalogAttribute;
use Wildberries\Domain\Helper\TypeResolver;
use Wildberries\Domain\Repository\AttributeInterface;
use Wildberries\Domain\Repository\CatalogInterface;
use Wildberries\Domain\Repository\Dwh\WbImportRepositoryInterface;

final readonly class Handler implements CommandHandlerInterface
{
    /**
     * @var string Источник атрибута - атрибуты категорий считаем характеристиками
     */
    private const string SOURCE = 'characteristic';

    /**
     * @throws Exception
     */
    public function __construct(
        private WbImportRepositoryInterface $wbRepository,
        private CatalogInterface            $catalogRepository,
        private AttributeInterface          $attributeRepository,
        private PersistenceInterface        $persistenceRepository,
        private TypeResolver                $typeResolver
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        /**
         * @var Catalog[] $catalogs
         */
        $catalogs = $this->catalogRepository->findAll();

        foreach ($catalogs as $catalog) {
            foreach ($this->wbRepository->findAttributesByCatalog($catalog['object_id']) as $attribute) {

                $localAttribute = $this->attributeRepository->findOneBy(
                    ['name' => $attribute->characteristicName]
                );

                $type = $this->typeResolver->getType($attribute->charcType, $attribute->maxCount);

                if (is_null($localAttribute)) {
                    $localAttribute = new Attribute(
                        attributeId: UuidBuild::uuid7()->toString(),
                        name: $attribute->characteristicName,
                        type: $type,
                        charcType: $attribute->charcType,
                        maxCount: $attribute->maxCount,
                        source: self::SOURCE,
                        measurement: $attribute->unitName,
                        isRequired: $attribute->isRequired,
                        isPopular: $attribute->popular
                    );
                } else {
                    $localAttribute->setName($attribute->characteristicName);
                    $localAttribute->setType($type);
                    $localAttribute->setCharcType($attribute->charcType);
                    $localAttribute->setMaxCount($attribute->maxCount);
                    $localAttribute->setMeasurement($attribute->unitName);
                    $localAttribute->setIsRequired($attribute->isRequired);
                    $localAttribute->setIsPopular($attribute->popular);
                    $localAttribute->setSource(self::SOURCE);
                }
                $this->persistenceRepository->persist($localAttribute);

                $catalogAttribute = new CatalogAttribute(
                    catalogAttributeId: UuidBuild::uuid7()->toString(),
                    catalogId: $catalog['catalog_id'],
                    attributeId: $localAttribute->getAttributeId()
                );
                $this->persistenceRepository->persist($catalogAttribute);
            }
            $this->persistenceRepository->flush();
        }
    }


}
