<?php

declare(strict_types=1);

namespace Pim\Application\Command\ProductLineEdit;

use Pim\Domain\Entity\ProductLine;
use Pim\Domain\Repository\Pim\ProductLineInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private ProductLineInterface $productLineRepository,
        private EntityStoreService   $entityStoreService
    ) {
    }

    public function __invoke(Command $command): void
    {
        $productLine = $this->productLineRepository->findOneByCriteria([
            'productLineId' => $command->productLineId,
            'brandId' => $command->brandId,
        ]);

        if (is_null($productLine)) {

            $productLineId = $command->productLineId;
            if (is_null($productLineId)) {
                $productLineId = Uuid::uuid7()->toString();
            }

            $productLine = new ProductLine(
                productLineId: $productLineId,
                brandId: $command->brandId,
                name: $command->name,
                code: $command->code
            );
        } else {
            $productLine->setName(name: $command->name);
        }

        $this->entityStoreService->commit($productLine);
    }
}
