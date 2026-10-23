<?php

declare(strict_types=1);

namespace Files\Application\Command\UploadFiles;

use Files\Domain\Entity\ProductImage;
use Files\Domain\Repository\ImageInterface;
use Files\Domain\Repository\PimInterface;
use League\Flysystem\FilesystemException;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private ImageInterface       $imageRepository,
        private PersistenceInterface $persistenceRepository,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    public function __invoke(Command $command): void
    {
        foreach ($command->images as $image) {

            $imageId = Uuid::uuid7()->toString();

            $this->imageRepository->write(
                productId: $command->productId,
                imageId: $imageId,
                content: $image->image,
                type: $image->type,
                size: $image->size
            );

            $this->persistenceRepository->persist(
                new ProductImage(
                    productId: $command->productId,
                    imageId: $imageId
                )
            );
        }
        $this->persistenceRepository->flush();
    }

}
