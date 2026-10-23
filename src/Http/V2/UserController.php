<?php

declare(strict_types=1);

namespace App\Http\V2;

use Pim\Application\Command\UserEdit\Command as UserEditCommand;
use Pim\Application\Query\User\GetAll\Query as GetAllQuery;
use Pim\Application\Query\User\GetById\Query as GetByIdQuery;
use Psr\Log\LoggerInterface;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Shared\Domain\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus,
        private readonly ValidationService   $validator,
        private readonly LoggerInterface     $logger,
    ) {
    }

    #[Route('/users', name: 'user_list', methods: 'post')]
    #[IsGranted('ROLE_USER_SHOW', message: 'no rights to watch user')]
    public function list(): JsonResponse
    {
        return $this->json($this->queryBus->dispatch(new GetAllQuery()));
    }

    #[Route('/user', name: 'user_one', methods: 'post')]
    #[IsGranted('ROLE_USER_SHOW', message: 'no rights to watch user')]
    public function show(Request $request): JsonResponse
    {
        $query = new GetByIdQuery(
            userId: $request->request->getString('userId')
        );

        $errors = $this->validator->validate($query);
        if (is_array($errors)) {
            return $this->json(['error' => $errors], 418);
        }

        $unit = $this->queryBus->dispatch($query);
        if (is_null($unit)) {
            return $this->json(['error' => 'Error: User not found'], 404);
        }
        return $this->json($unit);
    }

    #[Route('/user/edit', name: 'user_edit', methods: 'post')]
    #[IsGranted('ROLE_USER_EDIT', message: 'no rights to change user')]
    public function edit(Request $request): JsonResponse
    {
        $body = $request->request->all();

        $command = new UserEditCommand(
            userId: $body['userId'],
            roles: $body['roles'],
            units: $body['units'],
            brands: $body['brands'],
            productLines: $body['productLines'],
            sources: $body['sources'],
        );

        $errors = $this->validator->validate($command);
        if (is_array($errors)) {
            $this->logger->error('Gateway: UserController edit', $errors);
            return $this->json(['error' => $errors], 418);
        }

        $this->commandBus->dispatch($command);

        return $this->json(['message' => 'success']);
    }
}
