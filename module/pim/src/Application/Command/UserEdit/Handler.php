<?php

declare(strict_types=1);

namespace Pim\Application\Command\UserEdit;

use Pim\Domain\Entity\User;
use Pim\Domain\Repository\Pim\UserInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private UserInterface      $userRepository,
        private EntityStoreService $entityStoreService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if ($user instanceof User) {
            if ($command->roles != []) {
                $user->setRoles($command->roles);
            }

            if ($command->units != []) {
                $user->setUnits($command->units);
            }

            if ($command->brands != []) {
                $user->setBrands($command->brands);
            }

            if ($command->productLines != []) {
                $user->setProductLines($command->productLines);
            }

            if ($command->sources != []) {
                $user->setSources($command->sources);
            }

            $this->entityStoreService->commit($user);
        }
    }
}
