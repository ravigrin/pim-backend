<?php

declare(strict_types=1);

namespace OneC\Application\Command\CreateBrand;

use OneC\Domain\Repository\NomenclatureInterface;
use Shared\Domain\Command\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private NomenclatureInterface $nomenclatureRepository,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $in1c = $this->nomenclatureRepository->isBrandUpload($command->brandId);
        $this->nomenclatureRepository->exportBrand(
            brandId: $command->brandId,
            brandName: $command->brandName,
            isUpdate: $in1c
        );
    }
}
