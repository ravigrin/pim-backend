<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Wildberries\Domain\Entity\Price;
use Wildberries\Domain\Repository\PriceInterface;
use Exception;

/**
 * Репозиторий для работы с ценами Wildberries
 */
class PriceRepository implements PriceInterface
{
    /** @psalm-var EntityRepository<Price> */
    private EntityRepository $repository;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly Connection             $connection,
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Price::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Price|null
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Price {
        return $this->repository->findOneBy(
            $criteria,
            $orderBy
        );
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Price[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset,
        );
    }


    /**
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    public function getList(): array
    {
        $sql = "SELECT P.product_id, P.nm_id, P.brand, P.vendor_code, M.little, 
                       PR.price, PR.discount, PR.final_price, PR.net_cost, PR.production_cost, PR.production_cost_flag, C.name
                FROM wb_product P
                LEFT JOIN wb_price PR
                ON P.product_id = PR.product_id
                LEFT JOIN wb_catalog C
                ON P.catalog_id = C.catalog_id
                INNER JOIN wb_media M
                ON P.product_id = M.product_id
                WHERE P.export_status > 0 AND M.little IS NOT NULL AND M.number = 1";

        return $this->connection->executeQuery($sql)->fetchAllAssociative();
    }
}
