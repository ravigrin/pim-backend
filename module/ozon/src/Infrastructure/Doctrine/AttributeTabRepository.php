<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ozon\Domain\Entity\AttributeTab;
use Ozon\Domain\Repository\AttributeTabInterface;
use Shared\Domain\ValueObject\Uuid;

class AttributeTabRepository implements AttributeTabInterface
{
    /** @psalm-var EntityRepository<AttributeTab> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(AttributeTab::class);
    }

    /**
     * @return array<string, Uuid>
     */
    public function finAllWithAliasUuid(): array
    {
        $tabs = $this->repository->findBy([]);
        $result = [];
        foreach ($tabs as $tab) {
            $result[$tab->getAlias()] = $tab->getAttributeTabId();
        }
        return $result;
    }

    public function findByAlias(string $alias): ?AttributeTab
    {
        return $this->repository->findOneBy([
            'alias' => $alias,
            'isDeleted' => false,
        ]);
    }
}
