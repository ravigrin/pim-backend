<?php

declare(strict_types=1);

namespace Wildberries\EndPoint\Http\V1;

use App\Exception\UserNotFound;
use Pim\Domain\Entity\User;
use Pim\Domain\Exception\BrandNotFound;
use Pim\Domain\Exception\ProductLineNotFound;
use Pim\Domain\Exception\UnitNotFound;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Wildberries\Application\Command\Product\Make\Command as WbProductMakeCommand;

/** @psalm-suppress PropertyNotSetInConstructor */
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    /**
     * @throws UserNotFound
     * @throws UnitNotFound
     * @throws BrandNotFound
     * @throws ProductLineNotFound
     */
    #[Route('/product/edit', name: 'wb_product_edit', methods: 'post')]
    #[IsGranted('ROLE_PRODUCT_EDIT', message: 'no rights to edit product')]
    public function edit(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new UserNotFound();
        }

        $body = $request->request->all();
        $productId = $body['productId'];

        if (isset($body['wildberries']) && is_array($body['wildberries'])) {
            $wb = $body['wildberries'];
            $wbProduct = new WbProductMakeCommand(
                user: $user,
                productId: $productId,
                catalogId: $wb['catalogId'],
                union: $wb['union'],
                attributes: $wb['attributes']
            );

            $this->commandBus->dispatch($wbProduct);
        }

        return $this->json([
            'message' => 'success',
        ]);
    }
}
