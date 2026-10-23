<?php

declare(strict_types=1);

namespace Ozon\Application\Command\CatalogImport;

use Ozon\Domain\Entity\Catalog;
use Ozon\Domain\Repository\CatalogInterface;
use Ozon\Domain\Repository\Dwh\OzonImportInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\ValueObject\Uuid;
use Shared\Infrastructure\PersistenceRepository;

final readonly class Handler implements CommandHandlerInterface
{
    private const string TREE_PATH = 'Красота и гигиена';

    public function __construct(
        private OzonImportInterface   $ozonRepository,
        private CatalogInterface      $catalogRepository,
        private PersistenceRepository $persistenceRepository
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Command $command): void
    {
        $importCatalogs = $this->ozonRepository->findCatalogsByTreePath(
            treePath: self::TREE_PATH
        );
        foreach ($importCatalogs as $importCatalog) {
            $localCatalog = $this->catalogRepository->findOneByExternalId($importCatalog->typeId);
            if (is_null($localCatalog)) {
                $catalog = new Catalog(
                    catalogUuid: Uuid::build(),
                    treePath: $importCatalog->treePath,
                    level: $importCatalog->level,
                    catalogId: $importCatalog->parentCatalogId,
                    typeId: $importCatalog->typeId,
                    typeName: $importCatalog->typeName
                );
                $this->persistenceRepository->persist($catalog);
            }
            $this->persistenceRepository->flush();
        }
    }

}
