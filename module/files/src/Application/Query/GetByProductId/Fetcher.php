<?php

declare(strict_types=1);

namespace Files\Application\Query\GetByProductId;

use Files\Domain\Entity\ProductImage;
use Files\Domain\Repository\ImageInterface;
use Files\Domain\Repository\ProductImageInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductImageInterface $productImageRepository,
    ) {
    }

    public function __invoke(Query $query): array
    {
        return array_map(
            fn (ProductImage $productImage) => ['imageId' => $productImage->getImageId()],
            $this->productImageRepository->findByProductId($query->productId)
        );
    }
}
