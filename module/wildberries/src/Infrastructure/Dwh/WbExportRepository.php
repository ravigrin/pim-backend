<?php

namespace Wildberries\Infrastructure\Dwh;

use Shared\Infrastructure\PersistenceRepository;
use Wildberries\Application\Command\Price\Export\PriceDto;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Wildberries\Domain\Entity\Product;
use Wildberries\Domain\Repository\Dto\Export\WbCreateProductDto;
use Wildberries\Domain\Repository\Dto\Export\WbDivideProductDto;
use Wildberries\Domain\Repository\Dto\Export\WbUnionProductDto;
use Wildberries\Domain\Repository\Dto\Export\WbUpdateProductDto;
use Wildberries\Domain\Repository\ProductInterface;
use Wildberries\Domain\Repository\Dwh\WbExportRepositoryInterface;
use Exception;
use JsonException;

final readonly class WbExportRepository implements WbExportRepositoryInterface
{
    /**
     * @var int
     */
    private const int STATUS_SUCCESS = 2;

    /**
     * @var int
     */
    private const int STATUS_FAIL = 3;

    /**
     * @var Connection Соединение с DWH
     */
    private Connection $connection;

    public function __construct(
        private PersistenceRepository $persistenceRepository,
        private ProductInterface      $productRepository,
        private ManagerRegistry       $doctrine
    ) {
        /** @var Connection $dwh */
        $dwh = $this->doctrine->getConnection('dwh');
        $this->connection = $dwh;
    }

    /**
     * Отправляет карточку на создание
     * @param WbCreateProductDto[] $productsDto
     * @param string $seller
     * @return void
     * @throws \Doctrine\DBAL\Exception
     * @throws JsonException
     */
    public function create(array $productsDto, string $seller): void
    {
        $sql = "exec wb.cards_upload @SellerName = '%s', @Data = '%s'";
        $json = json_encode([$productsDto], JSON_THROW_ON_ERROR);
        $this->sendToDwh($sql, $json, $seller, $productsDto);
    }

    /**
     * Отправляет карточку на обновление
     * @param WbUpdateProductDto[] $productsDto
     * @param string $seller
     * @return void
     * @throws \Doctrine\DBAL\Exception
     * @throws JsonException
     * @throws Exception
     */
    public function update(array $productsDto, string $seller): void
    {
        $sql = "exec wb.cards_update @SellerName = '%s', @Data = '%s'";
        $json = json_encode($productsDto, JSON_THROW_ON_ERROR);
        $this->sendToDwh($sql, $json, $seller, $productsDto);
    }

    /**
     * Объединяет или разъединяет карточки товара
     * @param WbUnionProductDto|WbDivideProductDto $productsDto
     * @param string $seller
     * @return void
     * @throws \Doctrine\DBAL\Exception
     * @throws JsonException
     */
    public function unionOrDivide(WbUnionProductDto|WbDivideProductDto $productsDto, string $seller): void
    {
        $sql = "exec wb.cards_moveNm @SellerName = '%s', @Data = '%s'";
        $json = json_encode($productsDto, JSON_THROW_ON_ERROR);
        $this->sendToDwh($sql, $json, $seller, [$productsDto]);
    }

    /**
     * @param string $sql
     * @param string $json
     * @param string $seller
     * @param array|null $productsDto
     * @return void
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    private function sendToDwh(string $sql, string $json, string $seller, ?array $productsDto = null): void
    {
        $resp = $this->connection
            ->executeQuery(
                sql: sprintf($sql, $seller, $json),
            )->fetchOne();

        $resp = json_decode($resp, true);

        if(!$productsDto) {
            return;
        }

        if (is_array($resp) && isset($resp['error'])) {
            if (!$resp['error']) {
                $this->setStatusExport($productsDto, self::STATUS_SUCCESS);
            } else {
                $this->setStatusExport($productsDto, self::STATUS_FAIL);
            }
        }
    }

    /**
     * @param WbUpdateProductDto[]|WbCreateProductDto[] $productsDto
     * @param int $status
     * @return void
     * @throws Exception
     */
    private function setStatusExport(array $productsDto, int $status): void
    {
        foreach ($productsDto as $productDto) {
            $product = $this->productRepository->findOneBy(['nmId' => $productDto->nmId]);
            if (!$product instanceof Product) {
                throw new Exception(
                    "NOT FOUND Product by nmId: {$productDto->nmId} - WbExportRepository::setStatusExport()"
                );
            }
            $product->setExportStatus($status);
            $this->persistenceRepository->persist($product);
        }
        $this->persistenceRepository->flush();
    }

    /**
     * @param PriceDto[] $prices
     * @param string $seller
     * @return void
     * @throws JsonException
     * @throws \Doctrine\DBAL\Exception
     */
    public function priceUpdate(array $prices, string $seller): void
    {
        $sql = "exec wb.price_update @SellerName = '%s', @Data = '%s'";
        $json = json_encode($prices, JSON_THROW_ON_ERROR);
        $this->sendToDwh($sql, $json, $seller);
    }

}
