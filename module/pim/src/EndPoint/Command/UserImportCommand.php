<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Repository\Pim\UserInterface;
use Pim\Domain\Service\CreateUser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:user:import',
    description: 'Загрузка системного пользователя',
)] final class UserImportCommand extends Command
{
    public function __construct(
        private CreateUser    $createUserService,
        private UserInterface $userRepository,
        string                $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->userRepository->findByUsername('system');
        if (is_null($user)) {
            $this->createUserService->create('system', ['ROLE_ADMIN']);
        }

        return Command::SUCCESS;
    }
}
