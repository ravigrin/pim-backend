<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\ProductLine;
use Pim\Domain\Repository\Pim\ProductLineInterface;

final readonly class ProductLineRepository implements ProductLineInterface
{
    /** @psalm-var EntityRepository<ProductLine> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(ProductLine::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?ProductLine {
        return $this->repository->findOneBy(
            array_merge(
                $criteria,
                ['isDeleted' => false]
            ),
            $orderBy
        );
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return ProductLine[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy(
            array_merge(
                $criteria,
                ['isDeleted' => false]
            ),
            $orderBy,
            $limit,
            $offset,
        );
    }

    public function findUuidName(): array
    {
        $productLines = $this->findByCriteria([]);
        $result = [];
        foreach ($productLines as $productLine) {
            $result[$productLine->getProductLineId()] = $productLine->getName();
        }
        return $result;
    }

}
