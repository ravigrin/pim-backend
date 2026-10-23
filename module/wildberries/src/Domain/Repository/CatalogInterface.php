<?php

declare(strict_types=1);

namespace Wildberries\Domain\Repository;

use Shared\Domain\ValueObject\Uuid;
use Wildberries\Domain\Entity\Catalog;

/**
 * Репозиторий для работы с данными каталога Wildberries
 */
interface CatalogInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Catalog|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Catalog;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Catalog[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * Получение всех каталогов
     * @param bool $isActive
     * @param bool $isDeleted
     * @return array
     */
    public function findAll(bool $isActive = true, bool $isDeleted = false): array;

    /**
     * Поиск по идентификатору каталога
     * @param Uuid $catalogId
     * @param bool $isDeleted
     * @return array
     */
    public function findById(Uuid $catalogId, bool $isDeleted = false): array;

    /**
     * Поиск каталогов по objectId (wb id объекта каталога)
     *
     * @param int $objectId
     * @param bool $isDeleted
     * @return array
     */
    public function findByObjectId(int $objectId, bool $isDeleted = false): array;


    /**
     * Поиск по имени объекта каталога
     * @param string $name
     * @param bool $isDeleted
     * @return array
     */
    public function findByName(string $name, bool $isDeleted = false): array;

}
