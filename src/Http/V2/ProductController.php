<?php

declare(strict_types=1);

namespace App\Http\V2;

use App\Exception\SourceNotFound;
use App\Exception\UserNotFound;
use App\Factory\ProductQueryFactory;
use App\Factory\ProductsQueryFactory;
use Exception;
use Ozon\Application\Command\ProductEdit\Attribute as OzonAttribute;
use Ozon\Application\Command\ProductEdit\Command as OzonProductEditCommand;
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
use Wildberries\Application\Command\Product\Make\Command as WbProductMakeCommand;
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
     * @throws SourceNotFound
     */
    #[Route('/products', name: 'product_list', methods: 'post')]
    #[IsGranted('ROLE_PRODUCT_SHOW', message: 'no rights to watch products')]
    public function list(Request $request): JsonResponse
    {
        $query = ProductsQueryFactory::getQuery(
            source: $request->request->getString('source'),
        );
        return $this->json($this->queryBus->dispatch($query));
    }

    /**
     * @throws SourceNotFound
     */
    #[Route('/product', name: 'product_show', methods: 'post')]
    #[IsGranted('ROLE_PRODUCT_SHOW', message: 'no rights to watch products')]
    public function show(Request $request): JsonResponse
    {
        $query = ProductQueryFactory::getQuery(
            source: $request->request->getString('source'),
            productId: $request->request->getString('productId')
        );
        return $this->json($this->queryBus->dispatch($query));
    }

    /**
     * @throws UserNotFound
     * @throws UnitNotFound
     * @throws BrandNotFound
     * @throws ProductLineNotFound
     */
    #[Route('/product/edit', name: 'product_edit', methods: 'post')]
    #[IsGranted('ROLE_PRODUCT_EDIT', message: 'no rights to edit product')]
    public function edit(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new UserNotFound();
        }

        $body = $request->request->all();
        // 400 вместо KeyError/500 при отсутствии обязательных полей
        if (!isset($body['productId'])) {
            throw new \InvalidArgumentException('Field "productId" is required.');
        }
        $productId = (string) $body['productId'];

        if (isset($body['pim']) && is_array($body['pim'])) {
            $pim = $body['pim'];
            foreach (['unitId', 'brandId'] as $required) {
                if (!isset($pim[$required])) {
                    throw new \InvalidArgumentException("Field \"pim.$required\" is required.");
                }
            }

            // Валидация справочников через CQRS-запросы; вынос в общий RequestValidator — см. TODO.md.
            // Unit validate
            if (!$unit = $this->queryBus->dispatch(new UnitGetByIdQuery(unitId: $pim['unitId']))) {
                throw new UnitNotFound('Unit not found for given id.');
            }
            // Brand validate
            if (!$brand = $this->queryBus->dispatch(new BrandGetByIdQuery(brandId: $pim['brandId']))) {
                throw new BrandNotFound('Brand not found for given id.');
            }
            // ProductLine validate
            if ($productLine = ($pim['productLineId'] ?? null)) {
                if (!$productLine = $this->queryBus->dispatch(new ProductLineGetByIdQuery(productLineId: $pim['productLineId']))) {
                    throw new ProductLineNotFound('Product line not found for given id.');
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
        }

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
                vendorCode: $vendorCode
            );
            $this->commandBus->dispatch($command);
        }

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
