<?php

declare(strict_types=1);

namespace App\Http\V2;

use Pim\Application\Command\UnitEdit\Command as UnitEditCommand;
use Pim\Application\Query\Unit\GetAll\Query as GetAllQuery;
use Pim\Application\Query\Unit\GetById\Query as GetByIdQuery;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Shared\Domain\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UnitController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus,
        private readonly ValidationService   $validator,
    ) {
    }

    #[Route('/units', name: 'unit_list', methods: 'post')]
    #[IsGranted('ROLE_UNIT_SHOW', message: 'no rights to watch unit')]
    public function list(): JsonResponse
    {
        return $this->json($this->queryBus->dispatch(new GetAllQuery()));
    }

    #[Route('/unit', name: 'unit_one', methods: 'post')]
    #[IsGranted('ROLE_UNIT_SHOW', message: 'no rights to watch unit')]
    public function show(Request $request): JsonResponse
    {
        $unitId = $request->request->getString('unitId');
        $query = new GetByIdQuery(unitId: $unitId);

        $errors = $this->validator->validate($query);
        if (is_array($errors)) {
            return $this->json(['error' => $errors], 418);
        }

        $unit = $this->queryBus->dispatch($query);
        if (is_null($unit)) {
            return $this->json(['error' => 'Error: Unit not found'], 404);
        }
        return $this->json($unit);
    }

    #[Route('/unit/edit', name: 'unit_edit', methods: 'post')]
    #[IsGranted('ROLE_UNIT_EDIT', message: 'no rights to change unit')]
    public function edit(Request $request): JsonResponse
    {
        /** @var string|null $unitId */
        $unitId = $request->request->getString('unitId');
        $name = $request->request->getString('name');
        $code = $request->request->getString('code');

        $command = new UnitEditCommand(
            unitId: $unitId,
            name: $name,
            code: $code
        );

        $errors = $this->validator->validate($command);
        if (is_array($errors)) {
            return $this->json(['error' => $errors], 418);
        }

        $this->commandBus->dispatch($command);

        return $this->json(['message' => 'success']);
    }
}
