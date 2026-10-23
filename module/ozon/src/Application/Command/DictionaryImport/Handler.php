<?php

declare(strict_types=1);

namespace Ozon\Application\Command\DictionaryImport;

use Doctrine\DBAL\Exception;
use Ozon\Domain\Entity\Dictionary;
use Ozon\Domain\Repository\AttributeInterface;
use Ozon\Domain\Repository\CatalogInterface;
use Ozon\Domain\Repository\DictionaryInterface;
use Ozon\Domain\Repository\Dwh\OzonImportInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Exception\ValueObjectException;
use Shared\Domain\Repository\PersistenceInterface;
use Shared\Domain\ValueObject\Uuid;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private OzonImportInterface  $ozonRepository,
        private CatalogInterface     $catalogRepository,
        private AttributeInterface   $attributeRepository,
        private DictionaryInterface  $dictionaryRepository,
        private PersistenceInterface $persistenceRepository
    ) {
    }

    /**
     * @throws ValueObjectException
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        $catalogs = $this->catalogRepository->findAll();

        foreach ($catalogs as $catalog) {

            $attributes = $this->attributeRepository->findByCatalog($catalog->getCatalogUuid());

            foreach ($attributes as $attribute) {

                $ozonDictionary = $this->ozonRepository->findDictionary(
                    catalogId: $catalog->getCatalogId(),
                    attributeId: $attribute->getAttributeId()
                );

                $savedDictionary = $this->dictionaryRepository->findByExternalCatalogAttribute(
                    catalogId: $catalog->getCatalogId(),
                    attributeId: $attribute->getAttributeId(),
                );

                if (count($ozonDictionary) === count($savedDictionary)) {
                    continue;
                }

                foreach ($ozonDictionary as $ozonItem) {

                    $dictionary = $this->dictionaryRepository->findByExternalCatalogAttributeDictionary(
                        catalogId: $ozonItem->categoryId,
                        attributeId: $ozonItem->attributeId,
                        dictionaryId: $ozonItem->dictionaryId
                    );

                    if (is_null($dictionary)) {
                        $this->persistenceRepository->persist(
                            new Dictionary(
                                dictionaryUuid: Uuid::build(),
                                catalogUuid: $catalog->getCatalogUuid(),
                                attributeUuid: $attribute->getAttributeUuid(),
                                value: $ozonItem->value,
                                catalogId: $ozonItem->categoryId,
                                attributeId: $ozonItem->attributeId,
                                dictionaryId: $ozonItem->dictionaryId,
                                info: $ozonItem->info,
                                picture: $ozonItem->picture
                            )
                        );
                    }
                }
                $this->persistenceRepository->flush();
            };
        }
    }

}
