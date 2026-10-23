<?php

declare(strict_types=1);

namespace Pim\EndPoint\Http\V1;

use App\Exception\UserNotFound;
use Exception;
use Pim\Application\Command\ProductsMake\Command as PimNomenclatureMakeCommand;
use Pim\Application\Command\ProductMake\Attribute as PimAttribute;
use Pim\Application\Command\ProductMake\Command as PimProductEditCommand;
use Pim\Application\Query\Product\GetVendorCodeByUuid\Query as GetVendorCodeByUuidQuery;
use Pim\Application\Query\Unit\GetById\Query as UnitGetByIdQuery;
use Pim\Application\Query\Brand\GetById\Query as BrandGetByIdQuery;
use Pim\Application\Query\ProductLine\GetById\Query as ProductLineGetByIdQuery;
use Pim\Application\Query\Product\GetVendorCode\Query as ProductGetVendorCodeQuery;
use Pim\Application\Command\ProductPushToOneC\Command as ProductPushToOneCCommand;
use Pim\Domain\Entity\User;
use Pim\Domain\Exception\BrandNotFound;
use Pim\Domain\Exception\ProductLineNotFound;
use Pim\Domain\Exception\UnitNotFound;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Ramsey\Uuid\Uuid;

/** @psalm-suppress PropertyNotSetInConstructor */
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    /**
     * @throws UserNotFound
     * @throws UnitNotFound
     * @throws BrandNotFound
     * @throws ProductLineNotFound
     */
    #[Route('/product/edit', name: 'pim_product_edit', methods: 'post')]
    #[IsGranted('ROLE_PRODUCT_EDIT', message: 'no rights to edit product')]
    public function edit(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new UserNotFound();
        }

        $body = $request->request->all();

        $productId = $body['productId'];
        $pim = $body['pim'];

        if (!is_array($pim)) {
            return $this->json([
                'message' => 'Not found pim item',
            ]);
        }

        // Unit validate
        if (!$unit = $this->queryBus->dispatch(new UnitGetByIdQuery(unitId: $pim['unitId']))) {
            throw new UnitNotFound('Http/V2/ProductController.php::edit()');
        }
        // Brand validate
        if (!$brand = $this->queryBus->dispatch(new BrandGetByIdQuery(brandId: $pim['brandId']))) {
            throw new BrandNotFound('Http/V2/ProductController.php::edit()');
        }
        // ProductLine validate
        if ($productLine = $pim['productLineId']) {
            if (!$productLine = $this->queryBus->dispatch(new ProductLineGetByIdQuery(productLineId: $pim['productLineId']))) {
                throw new ProductLineNotFound('Http/V2/ProductController.php::edit()');
            }
        }

        if (!$productId) {
            // Generate UUID
            $productUuid = Uuid::uuid7()->toString();
            // Generate new article
            $vendorCode = $this->queryBus->dispatch(
                new ProductGetVendorCodeQuery(
                    unit: $unit,
                    brand: $brand,
                    isKit: (bool)$pim['isKit'],
                    productLine: $productLine
                )
            );
        } else {
            $vendorCode = $this->queryBus->dispatch(new GetVendorCodeByUuidQuery(
                productId: $productId
            ));
        }

        $attributes = [];
        foreach ($pim['attributes'] as $attribute) {
            $attributes[] = new PimAttribute(
                attributeId: $attribute['attributeId'],
                value: $attribute['value']
            );
        }
        $command = new PimProductEditCommand(
            user: $user,
            attributes: $attributes,
            productId: $productUuid ?? $productId,
            unit: $unit,
            brand: $brand,
            productLine: $productLine,
            catalogId: $pim['catalogId'],
            union: $pim['union'],
            isKit: (bool)$pim['isKit'],
            vendorCode: $vendorCode
        );

        $this->commandBus->dispatch($command);

        return $this->json([
            'message' => 'success',
            'productId' => $productUuid ?? $productId,
            'vendorCode' => $vendorCode ?? null
        ]);
    }

    /**
     *
     * @throws UserNotFound
     * @throws Exception
     */
    #[Route('/nomenclatures', name: 'nomenclatures_create', methods: 'post')]
    #[IsGranted('ROLE_PRODUCT_EDIT', message: 'no rights to create nomenclatures')]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new UserNotFound();
        }

        if (!$data = $request->request->all()) {
            throw new Exception('Error! Data is empty.');
        }

        foreach ($data as $nomenclature) {
            $command = new PimNomenclatureMakeCommand(
                user: $user,
                name: $nomenclature['name'],
                unit: $nomenclature['unit'],
                brand: $nomenclature['brand'],
                isKit: $nomenclature['set'],
                productLine: $nomenclature['productLine'],
                status: $nomenclature['status'],
                vendorCode: $nomenclature['SKU']
            );
            $this->commandBus->dispatch($command);
        }

        return $this->json(['message' => 'success']);
    }

    #[Route('/product/pushToOneC', name: 'product_push_to_one_c', methods: 'post')]
    public function pushProduct(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new UserNotFound();
        }

        $this->commandBus->dispatch(
            new ProductPushToOneCCommand(
                productId: $request->request->getString('productId')
            )
        );

        return $this->json(['message' => 'success']);
    }


}
