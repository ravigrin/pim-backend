<?php

namespace Files\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Files\Domain\Entity\ProductImage;
use Files\Domain\Repository\ProductImageInterface;

class ProductImageRepository implements ProductImageInterface
{
    /** @psalm-var EntityRepository<ProductImage> */
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(ProductImage::class);
    }

    /**
     * @return ProductImage[]
     */
    public function findByProductId(string $productId): array
    {
        return $this->repository->findBy(['productId' => $productId]);
    }

    public function findByImageId(string $imageId): ?ProductImage
    {
        return $this->repository->findOneBy(['imageId' => $imageId]);
    }
}
