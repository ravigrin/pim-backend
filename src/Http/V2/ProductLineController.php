<?php

declare(strict_types=1);

namespace App\Http\V2;

use Pim\Application\Command\ProductLineEdit\Command as UpdateUnitCommand;
use Pim\Application\Query\ProductLine\GetAll\Query as GetAllQuery;
use Pim\Application\Query\ProductLine\ProductLineDto;
use Pim\Domain\Entity\User;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
final class ProductLineController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    #[Route('productLines', name: 'productLine_list', methods: 'post')]
    public function list(Request $request): JsonResponse
    {
        $result = [];

        $user = $this->getUser();
        if ($user instanceof User) {
            $query = new GetAllQuery(
                brandId: $request->request->getString('brandId'),
                username: $user->getUserIdentifier()
            );
            /** @var ProductLineDto[] $result */
            $result = $this->queryBus->dispatch($query);
        }

        return $this->json($result);
    }

    #[Route('productLine/edit', name: 'productLine_edit', methods: 'post')]
    public function edit(Request $request): JsonResponse
    {
        $body = $request->request->all();

        $command = new UpdateUnitCommand(
            brandId: $body['brandId'],
            name: $body['name'],
            code: $body['code'],
            productLineId: $body['productLineId'] ?? null,
        );

        $this->commandBus->dispatch($command);

        return $this->json(['message' => 'success']);
    }
}
