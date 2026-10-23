<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ozon\Domain\Entity\Product;
use Ozon\Domain\Repository\ProductInterface;

class ProductRepository implements ProductInterface
{
    /** @psalm-var EntityRepository<Product> */
    private EntityRepository $repository;

    private Connection $connection;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Product::class);
        $this->connection = $this->entityManager->getConnection();
    }

    public function findProductsForFront(): array
    {
        $sql = <<<SQL
            select pp.product_uuid as productId, 
                   pp.offer_id as vendorCode, 
                   JSON_VALUE(ppa1.value, '$[0]') as productName
            from ozon_product pp
            left join ozon_product_attribute ppa1 on pp.product_uuid = ppa1.product_uuid 
                and ppa1.attribute_uuid = (SELECT ozon_attribute.attribute_uuid FROM ozon_attribute WHERE alias = 'name')
        SQL;

        return $this->connection->executeQuery($sql)->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function findProductsWithPrice(): array
    {
        $sql = <<<SQL
        select  pp.product_uuid as productId,
            JSON_VALUE(ppa4.value, '$[0]') AS name,
            pp.offer_id as vendorCode,
            pp.barcode as barcode,
            JSON_VALUE(ppa1.value, '$[0]') AS price,
            JSON_VALUE(ppa2.value, '$[0]') AS oldPrice,
            JSON_VALUE(ppa3.value, '$[0]') AS salePercent,
            JSON_VALUE(ppa5.value, '$[0]') AS costPrice,
            JSON_VALUE(ppa6.value, '$[0]') AS productionPrice,
            JSON_VALUE(ppa7.value, '$[0]') AS productionPriceFlag
        from ozon_product pp
                 left join
             ozon_product_attribute ppa1 on pp.product_uuid = ppa1.product_uuid
                 and ppa1.attribute_uuid = (
                     SELECT ozon_attribute.attribute_uuid FROM ozon_attribute WHERE alias = 'price'
                 )
                 left join
             ozon_product_attribute ppa2 on pp.product_uuid = ppa2.product_uuid
                 and ppa2.attribute_uuid = (
                     SELECT ozon_attribute.attribute_uuid FROM ozon_attribute WHERE alias = 'oldPrice'
                 )
                 left join
             ozon_product_attribute ppa3 on pp.product_uuid = ppa3.product_uuid
                 and ppa3.attribute_uuid = (
                     SELECT ozon_attribute.attribute_uuid FROM ozon_attribute WHERE alias = 'salePercent'
                 )
                 left join
             ozon_product_attribute ppa4 on pp.product_uuid = ppa4.product_uuid
                 and ppa4.attribute_uuid = (
                     SELECT ozon_attribute.attribute_uuid FROM ozon_attribute WHERE alias = 'name'
                 )
                 left join
             ozon_product_attribute ppa5 on pp.product_uuid = ppa5.product_uuid
                 and ppa5.attribute_uuid = (
                     SELECT ozon_attribute.attribute_uuid FROM ozon_attribute WHERE alias = 'costPrice'
                 )
                 left join
             ozon_product_attribute ppa6 on pp.product_uuid = ppa6.product_uuid
                 and ppa6.attribute_uuid = (
                     SELECT ozon_attribute.attribute_uuid FROM ozon_attribute WHERE alias = 'productionPrice'
                 )
                 left join
             ozon_product_attribute ppa7 on pp.product_uuid = ppa7.product_uuid
                 and ppa7.attribute_uuid = (
                     SELECT ozon_attribute.attribute_uuid FROM ozon_attribute WHERE alias = 'productionPriceFlag'
                 )
        where pp.export > 0
        SQL;

        /** @var array{
         * productId: string,
         * name: string,
         * vendorCode: string,
         * barcode: string,
         * price: string,
         * salePercent: string,
         * oldPrice: string,
         * costPrice: string,
         * productionPrice: string,
         * productionPriceFlag: bool|null
         * }[] $products
         */
        return $this->connection->executeQuery($sql)->fetchAllAssociative();
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Product {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Product[]
     */
    public function findByCriteria(
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


}
