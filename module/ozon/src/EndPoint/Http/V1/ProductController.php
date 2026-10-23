<?php

declare(strict_types=1);

namespace Ozon\EndPoint\Http\V1;

use App\Exception\UserNotFound;
use Ozon\Application\Command\ProductEdit\Attribute as OzonAttribute;
use Ozon\Application\Command\ProductEdit\Command as OzonProductEditCommand;
use Pim\Domain\Entity\User;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** @psalm-suppress PropertyNotSetInConstructor */
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    #[Route('/product/edit', name: 'ozon_product_edit', methods: 'post')]
    #[IsGranted('ROLE_PRODUCT_EDIT', message: 'no rights to edit product')]
    public function edit(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new UserNotFound();
        }

        $body = $request->request->all();
        $productId = $body['productId'];

        $ozon = null;
        if (isset($body['ozon'])) {
            $ozon = $body['ozon'];
        }
        if (is_array($ozon) &&
            is_string($productId) &&
            !empty($ozon['attributes']) &&
            !empty($ozon['catalogId'])
        ) {
            $attributes = [];
            foreach ($ozon['attributes'] as $attribute) {
                $attributes[] = new OzonAttribute(
                    attributeId: $attribute['attributeId'],
                    value: $attribute['value']
                );
            }

            $command = new OzonProductEditCommand(
                user: $user,
                catalogId: $ozon['catalogId'],
                attributes: $attributes,
                union: $ozon['union'],
                productId: $productId,
                vendorCode: $ozon['vendorCode']
            );
            $this->commandBus->dispatch($command);
        }

        return $this->json([
            'message' => 'success',
        ]);
    }

}
