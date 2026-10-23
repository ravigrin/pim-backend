<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ozon\Domain\Entity\CatalogAttribute;
use Ozon\Domain\Repository\CatalogAttributeInterface;
use Shared\Domain\ValueObject\Uuid;

class CatalogAttributeRepository implements CatalogAttributeInterface
{
    /** @psalm-var EntityRepository<CatalogAttribute> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(CatalogAttribute::class);
    }

    public function findOneByCatalogIdAttributeId(Uuid $catalogId, Uuid $attributeId): ?CatalogAttribute
    {
        return $this->repository->findOneBy([
            'catalogId' => $catalogId,
            'attributeId' => $attributeId
        ]);
    }
}
