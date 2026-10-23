<?php

declare(strict_types=1);

namespace Pim\Application\Command\UnitEdit;

use Pim\Domain\Entity\Unit;
use Pim\Domain\Repository\Pim\UnitInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private UnitInterface      $unitRepository,
        private EntityStoreService $entityStoreService
    ) {
    }

    public function __invoke(Command $command): void
    {
        if (empty($command->unitId)) {

            $unit = new Unit(
                unitId: Uuid::uuid7()->toString(),
                name: $command->name,
                code: $command->code,
            );
            $this->entityStoreService->commit($unit);

            return;
        }

        $unit = $this->unitRepository->findOneByCriteria([
            'unitId' => $command->unitId
        ]);

        if ($unit instanceof Unit) {
            $unit->setName($command->name);

            $this->entityStoreService->commit($unit);
        }

    }
}
