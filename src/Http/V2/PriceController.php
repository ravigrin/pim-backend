<?php

declare(strict_types=1);

namespace App\Http\V2;

use App\Exception\SourceNotFound;
use App\Exception\UserNotFound;
use App\Factory\PriceQueryFactory;
use App\Http\V2\RequestDto\PriceDto;
use Ozon\Application\Command\ProductPriceEdit\Command as OzonProductPriceEditCommand;
use Pim\Domain\Entity\User;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wildberries\Application\Command\Price\Make\Command as WbPriceMakeCommand;

/** @psalm-suppress PropertyNotSetInConstructor */
final class PriceController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    /**
     * Получение списка цен
     * @param Request $request
     * @return JsonResponse
     * @throws SourceNotFound
     */
    #[Route('/prices', name: 'price_list', methods: 'post')]
    public function list(Request $request): JsonResponse
    {
        $source = $request->request->getString('source');

        $userName = $this->getUser()->getUserIdentifier();
        $query = PriceQueryFactory::getQuery(
            source: $source,
            username: $userName
        );

        return $this->json($this->queryBus->dispatch($query));
    }

    /**
     * Редактирование цены
     * @param Request $request
     * @return JsonResponse
     * @throws UserNotFound
     * @throws SourceNotFound
     */
    #[Route('/price/edit', name: 'price_edit', methods: 'post')]
    public function edit(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new UserNotFound();
        }
        $userName = $this->getUser()->getUserIdentifier();

        $body = $request->request->all();
        if (!isset($body['prices'])) {
            return $this->json(['message' => 'Error! Price array is empty']);
        }

        $prices = array_map(
            fn (array $price): PriceDto => new PriceDto(
                productId: $price['productId'],
                price: $price['price'],
                discount: $price['discount'],
                totalPrice: $price['totalPrice'],
                costPrice: $price['costPrice']
            ),
            $body['prices']
        );

        $command = match ($request->request->getString('source')) {
            'ozon' => new OzonProductPriceEditCommand(username: $userName, prices: $prices),
            'wildberries' => new WbPriceMakeCommand(username: $userName, prices: $prices),
            default => throw new SourceNotFound("Not Found source: " . $request->request->getString('source'))
        };

        $this->commandBus->dispatch($command);

        return $this->json(['message' => 'success']);
    }

    /**
     * Рассчет себестоимости
     * @param Request $request
     * @return JsonResponse
     * @throws UserNotFound
     * @throws SourceNotFound
     */
    #[Route('/price/net-cost', name: 'price_net_cost', methods: 'post')]
    public function netCost(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new UserNotFound();
        }
        $userName = $this->getUser()->getUserIdentifier();

        $query = PriceQueryFactory::getNetCostQuery(
            source: $request->request->getString('source'),
            username: $userName,
            productId: $request->request->getString('productId'),
            finalPrice: $request->request->getInt('finalPrice')
        );

        return $this->json($this->queryBus->dispatch($query));
    }
}
