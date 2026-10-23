<?php

declare(strict_types=1);

namespace Files\Application\Query\GetByImageId;

use Files\Domain\Repository\ImageInterface;
use Files\Domain\Repository\ProductImageInterface;
use League\Flysystem\FilesystemException;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductImageInterface $productImageRepository,
        private ImageInterface        $imageRepository,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    public function __invoke(Query $query): array
    {
        $images = [];
        foreach ($query->imagesId as $imageId) {
            $productImage = $this->productImageRepository->findByImageId($imageId);
            if (is_null($productImage)) {
                continue;
            }

            $content = $this->imageRepository->read(
                productId: $productImage->getProductId(),
                imageId: $productImage->getImageId(),
            )->content;

            $images[] = [
                'imageId' => $imageId,
                'image' => $content
            ];
        }
        return $images;
    }
}
