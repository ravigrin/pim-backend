<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ozon\Domain\Entity\Dictionary;
use Ozon\Domain\Repository\DictionaryInterface;
use Shared\Domain\ValueObject\Uuid;

class DictionaryRepository implements DictionaryInterface
{
    /** @psalm-var EntityRepository<Dictionary> */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Dictionary::class);
    }

    public function findByExternalId(int $externalId): ?Dictionary
    {
        return $this->repository->findOneBy([
            'dictionaryId' => $externalId,
            'isDeleted' => false,
        ]);
    }

    /**
     * @return Dictionary[]
     */
    public function findByAttributeAndCatalog(string $catalogId, string $attributeId): array
    {
        return $this->repository->findBy([
            'catalogUuid' => $catalogId,
            'attributeUuid' => $attributeId,
            'isDeleted' => false,
        ]);
    }

    /**
     * @return Dictionary[]
     */
    public function findByAttribute(int $attributeId): array
    {
        return $this->repository->findBy([
            'attributeId' => $attributeId,
            'isActive' => true,
            'isDeleted' => false,
        ]);
    }

    /**
     * @return Dictionary[]
     */
    public function findByAttributeValue(int $attributeId, string $value): array
    {
        return $this->repository->findBy([
            'attributeId' => $attributeId,
            'value' => $value,
            'isActive' => true,
            'isDeleted' => false,
        ]);
    }

    public function findByCatalogAttributeValue(
        Uuid   $attributeId,
        Uuid   $catalogId,
        string $value
    ): ?Dictionary {
        return $this->repository->findOneBy([
            'catalogUuid' => $catalogId->getValue(),
            'attributeUuid' => $attributeId->getValue(),
            'value' => $value,
            'isActive' => true,
            'isDeleted' => false,
        ]);
    }

    public function findByExternalCatalogValue(int $catalogId, string $value): ?Dictionary
    {
        return $this->repository->findOneBy([
            'catalogId' => $catalogId,
            'value' => $value,
        ]);
    }

    public function findByExternalCatalogAttributeDictionary(
        int $catalogId,
        int $attributeId,
        int $dictionaryId
    ): ?Dictionary {
        return $this->repository->findOneBy([
            'catalogId' => $catalogId,
            'attributeId' => $attributeId,
            'dictionaryId' => $dictionaryId,
            'isActive' => true,
            'isDeleted' => false,
        ]);
    }

    /**
     * @return Dictionary[]
     */
    public function findByExternalCatalogAttribute(
        int $catalogId,
        int $attributeId,
    ): array {
        return $this->repository->findBy([
            'catalogId' => $catalogId,
            'attributeId' => $attributeId,
            'isActive' => true,
            'isDeleted' => false,
        ]);
    }
}
