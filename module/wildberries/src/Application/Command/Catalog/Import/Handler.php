<?php

namespace Wildberries\Application\Command\Catalog\Import;

use Exception;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid as UuidBuild;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Wildberries\Domain\Entity\Catalog;
use Wildberries\Domain\Repository\CatalogInterface;
use Wildberries\Domain\Repository\Dto\WbCatalogDto;
use Wildberries\Domain\Repository\Dwh\WbImportRepositoryInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private LoggerInterface             $logger,
        private WbImportRepositoryInterface $wbRepository,
        private CatalogInterface            $catalogRepository,
        private EntityStoreService          $entityStoreService,
        private ParameterBagInterface       $parameterBag
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        if (!$catalogs = $this->parameterBag->get('wildberries')['catalogs']) {
            $this->logger->critical('Not FOUND wildberries.catalogs settings:
            src/Wildberries/Application/Command/ImportCatalogs/Handler.php
            ');
        }

        $level = 1;
        foreach ($catalogs as $id => $name) {
            $this->initBaseCatalog((int)$id, $name);
            $this->recursiveImportCatalogs([(int)$id], $level);
        }
    }


    /**
     * Добавляет корневую директорию каталога, если запись отсутствует
     * @throws Exception
     */
    private function initBaseCatalog(int $catalogId, string $name): void
    {
        $catalog = $this->catalogRepository->findByObjectId($catalogId);

        if (empty($catalog)) {
            $catalog = new Catalog(
                catalogId: UuidBuild::uuid7()->toString(),
                objectId: $catalogId,
                name: $name,
                isVisible: true,
                level: 1
            );

            $this->entityStoreService->commit($catalog);
        }
    }

    /**
     * Рекурсивно проходит по каталогу с переданным идентификатором, мапит на DTO и сохраняет в БД
     * @param int[] $catalogsId
     * @throws Exception
     */
    private function recursiveImportCatalogs(array $catalogsId, int &$level): void
    {
        ++$level;
        $catalogs = $this->wbRepository->findCatalogsByParents($catalogsId);

        $this->saveToDatabase($catalogs, $level);
        $catalogChildId = array_map(static fn (WbCatalogDto $catalog): int => $catalog->categoryId, $catalogs);

        if ($catalogChildId !== []) {
            ++$level;
            $this->recursiveImportCatalogs($catalogChildId, $level);
        }
    }

    /**
     * @param WbCatalogDto[] $catalogs
     * @param int $level
     * @return void
     * @throws Exception
     */
    private function saveToDatabase(array $catalogs, int $level): void
    {
        foreach ($catalogs as $catalogDto) {
            $catalog = $this->catalogRepository->findByObjectId($catalogDto->categoryId);
            if (!empty($catalog)) {
                continue;
            }

            $catalog = new Catalog(
                catalogId: UuidBuild::uuid7()->toString(),
                objectId: $catalogDto->categoryId,
                name: $catalogDto->categoryName,
                isVisible: true,
                level: $level,
                parentId: $catalogDto->parentCategoryId ?? null
            );

            $this->entityStoreService->commit($catalog);
        }
    }

}
