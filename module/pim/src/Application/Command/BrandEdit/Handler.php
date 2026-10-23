<?php

declare(strict_types=1);

namespace Pim\Application\Command\BrandEdit;

use Shared\Domain\Command\CommandHandlerInterface;
use Pim\Domain\Entity\Brand;
use Pim\Domain\Repository\Pim\BrandInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private BrandInterface     $brandRepository,
        private EntityStoreService $entityStoreService
    ) {
    }

    public function __invoke(Command $command): void
    {
        $brand = $this->brandRepository->findOneByCriteria(
            ['brandId' => $command->brandId]
        );

        if (is_null($brand)) {
            $brand = new Brand(
                brandId: Uuid::uuid7()->toString(),
                name: $command->name,
                code: $command->code
            );
        } else {
            $brand->setName($command->name);
        }

        $brand->pushToOneC();

        $this->entityStoreService->commit($brand);

    }
}
