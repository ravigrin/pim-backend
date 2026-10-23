<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\Brand;
use Pim\Domain\Repository\Pim\BrandInterface;

final readonly class BrandRepository implements BrandInterface
{
    /** @psalm-var EntityRepository<Brand> */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Brand::class);
    }

    public function findByUserId(string $userId): array
    {
        //        $conn = $this->entityManager->getConnection();
        //        $sql = 'select distinct(pa.product_id) as id
        //                from pim_ pa
        //                left join dbo.attribute a on pa.attribute_id = a.id
        //                where a.channel_id = :channelId';
        //        $resultSet = $conn->executeQuery($sql, ['channelId' => $channelId]);
        //        $result = $resultSet->fetchAllAssociative();
        //        $productIds = array_column($result, 'id');

        return $this->findByCriteria([
            'brandId' => []
        ]);
    }

    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy(
            criteria: $criteria,
            orderBy: $orderBy,
            limit: $limit,
            offset: $offset
        );
    }

    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Brand {
        return $this->repository->findOneBy(
            criteria: $criteria,
            orderBy: $orderBy,
        );
    }

    public function findUuidName(): array
    {
        $brands = $this->findByCriteria([]);
        $result = [];
        foreach ($brands as $brand) {
            $result[$brand->getBrandId()] = $brand->getName();
        }
        return $result;
    }

    public function findNameByProduct(string $productId): ?Brand
    {
        $sql = <<<SQL
            select brand_id 
            from pim_product 
            where product_id = '%s'
        SQL;

        $brandId = $this->entityManager
            ->getConnection()
            ->executeQuery(sprintf($sql, $productId))
            ->fetchFirstColumn();

        return $this->findOneByCriteria(["brandId" => array_shift($brandId)]);
    }

}
