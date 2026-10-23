<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\ProductAttribute;
use Pim\Domain\Repository\Pim\ProductAttributeInterface;

final readonly class ProductAttributeRepository implements ProductAttributeInterface
{
    /** @psalm-var EntityRepository<ProductAttribute> */
    private EntityRepository $repository;

    private Connection $connection;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(ProductAttribute::class);
        $this->connection = $this->entityManager->getConnection();

    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?ProductAttribute {
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
     * @return ProductAttribute[]
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

    public function findProductAttribute(string $productId, string $alias): ?ProductAttribute
    {
        $sql = <<<SQL
            select attribute_id from pim_attribute where alias = '%s'
        SQL;

        $attribute = $this->connection->executeQuery(sprintf($sql, $alias))->fetchFirstColumn();

        return $this->findOneByCriteria(
            [
                "productId" => $productId,
                "attributeId" => array_shift($attribute)
            ]
        );
    }

}
