<?php

declare(strict_types=1);

namespace App\Http\V2;

use Pim\Application\Command\BrandEdit\Command as BrandEditCommand;
use Pim\Application\Query\Brand\BrandDto;
use Pim\Application\Query\Brand\GetById\Query as GetByIdQuery;
use Pim\Application\Query\Brand\GetByUser\Query as GetByUserQuery;
use Pim\Domain\Entity\User;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** @psalm-suppress PropertyNotSetInConstructor */
final class BrandController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    #[Route('/brands', name: 'brand_list', methods: 'post')]
    #[IsGranted('ROLE_BRAND_SHOW', message: 'no rights to watch brand')]
    public function list(): JsonResponse
    {
        $result = [];

        $user = $this->getUser();
        if ($user instanceof User) {
            /** @var BrandDto[] $brands */
            $brands = $this->queryBus->dispatch(
                new GetByUserQuery($user->getUserIdentifier())
            );
            $result = array_map(static fn (BrandDto $brand): array => (array)$brand, $brands);
        }

        return $this->json($result);
    }

    #[Route('/brand', name: 'brand_show', methods: 'post')]
    #[IsGranted('ROLE_BRAND_SHOW', message: 'no rights to watch brand')]
    public function one(Request $request): JsonResponse
    {
        $query = new GetByIdQuery(
            brandId: $request->request->getString('brandId')
        );

        return $this->json((array)$this->queryBus->dispatch($query));
    }

    #[Route('/brand/edit', name: 'brand_edit', methods: 'post')]
    #[IsGranted('ROLE_BRAND_EDIT', message: 'no rights to change brand')]
    public function edit(Request $request): JsonResponse
    {
        $command = new BrandEditCommand(
            brandId: $request->request->getString('uuid'),
            name: $request->request->getString('name'),
            code: $request->request->getString('code')
        );
        $this->commandBus->dispatch($command);

        return $this->json(['message' => 'success']);
    }

}
