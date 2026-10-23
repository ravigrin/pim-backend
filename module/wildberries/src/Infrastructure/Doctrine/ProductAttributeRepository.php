<?php

declare(strict_types=1);

namespace Wildberries\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Wildberries\Application\Query\Product\GetCodeAndLinkByUuid\ProductCodeAndLinkDto;
use Wildberries\Domain\Entity\ProductAttribute;
use Wildberries\Domain\Repository\Dto\CharacteristicDto;
use Wildberries\Domain\Repository\ProductAttributeInterface;
use Exception;

/**
 * Репозиторий для работы с характеристиками (атрибутами) товаров Wildberries
 */
class ProductAttributeRepository implements ProductAttributeInterface
{
    /** @psalm-var EntityRepository<ProductAttribute> */
    private EntityRepository $repository;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly Connection             $connection,
        private readonly EntityManagerInterface $entityManager
    )
    {
        $this->repository = $this->entityManager->getRepository(ProductAttribute::class);
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return ProductAttribute|null
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?ProductAttribute
    {
        return $this->repository->findOneBy(
            $criteria,
            $orderBy
        );
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return ProductAttribute[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array
    {
        return $this->repository->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset,
        );
    }

    /**
     * @inheritDoc
     * @return CharacteristicDto[]
     * @throws Exception
     */
    public function getCharacteristics(string $productId): array
    {
        $sql = "SELECT PA.value, A.name, A.type 
                FROM wb_product_attribute PA
                LEFT JOIN wb_attribute A on A.attribute_id = PA.attribute_id
                WHERE A.attribute_id IN (
                    SELECT attribute_id
                    FROM wb_attribute
                    WHERE alias IS NULL) 
                AND PA.product_id = '%s'";

        $charcs = $this->connection->executeQuery(sprintf($sql, $productId))->fetchAllAssociative();

        return array_map(static fn(array $charc): CharacteristicDto => new CharacteristicDto(
            name: $charc['name'],
            value: match ($charc['type']) {
                'integer', 'string' => (string)json_decode($charc['value'])[0],
                default => $charc['value']
            }
        ), $charcs);
    }

    /**
     * @inheritDoc
     * @return ProductCodeAndLinkDto|null
     * @throws Exception
     */
    public function getCodeAndLink(string $productId): ?ProductCodeAndLinkDto
    {
        $sql = "SELECT P.vendor_code, JSON_VALUE(PA.value, '$[0]') link
                FROM wb_product P
                LEFT JOIN wb_product_attribute PA on P.product_id = PA.product_id
                LEFT JOIN wb_attribute A on PA.attribute_id = A.attribute_id
                WHERE A.alias = 'publicProductLink'
                AND P.product_id = ?";

        $data = $this->connection->executeQuery(
            sql: $sql, params: [$productId]
        )->fetchAssociative();

        if (!$data) {
            return null;
        }

        return new ProductCodeAndLinkDto(
            vendorCode: $data['vendor_code'],
            publicLink: $data['link']
        );
    }

    /**
     * @param $productId
     * @return string|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function getVendorCode($productId): ?string
    {
         return $this->connection->executeQuery(
             sql: "SELECT P.vendor_code
                    FROM wb_product P
                    WHERE P.product_id = ?", params: [$productId]
         )->fetchOne();
    }
}
