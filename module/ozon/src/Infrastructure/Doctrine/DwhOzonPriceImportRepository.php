<?php

declare(strict_types=1);

namespace Ozon\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Ozon\Domain\Repository\Dwh\OzonPriceInterface;
use Psr\Log\LoggerInterface;

class DwhOzonPriceImportRepository implements OzonPriceInterface
{
    private Connection $connection;

    public function __construct(
        private ManagerRegistry $doctrine,
        private LoggerInterface $logger,
    ) {
        /** @var Connection $dwh */
        $dwh = $this->doctrine->getConnection('dwh');
        $this->connection = $dwh;
    }

    /**
     * @throws Exception
     */
    public function findCostPriceBy(string $vendorCode, float $sellingPrice): array
    {
        $sql = <<<SQL
            select ozon.fn_Себестоимость(
                (select :vendorCode   as Артикул, 
                        getdate()     as Дата, 
                        :sellingPrice as ЦенаПродажи
                for json path, include_null_values, without_array_wrapper)
            )
        SQL;

        $query = $this->connection->prepare($sql);
        $query->bindValue('vendorCode', $vendorCode);
        $query->bindValue('sellingPrice', $sellingPrice);

        /** @var string $json */
        $json = $query->executeQuery()->fetchOne();
        return json_decode(
            json: $json,
            associative: true
        );
    }

    /**
     * @throws Exception
     */
    public function updatePrice(
        string $offerId,
        int    $productId,
        int    $minPrice,
        int    $oldPrice,
        int    $price
    ): void {

        $prices["prices"][] = [
            "auto_action_enabled" => "UNKNOWN",
            "currency_code" => "RUB",
            "min_price" => (string)$minPrice,
            "offer_id" => $offerId,
            "old_price" => (string)$oldPrice,
            "price" => (string)$price,
            "price_strategy_enabled" => "UNKNOWN",
            "product_id" => $productId
        ];

        $jsonPrice = json_encode($prices);
        $ozonAccount = $_ENV['OZON_CLIENT_ID'];

        $result = $this->connection->executeQuery(sprintf(
            "exec ozon.send_prices @ClientId = '%s', @Data = '%s'",
            $ozonAccount,
            $jsonPrice
        ));
        $this->logger->info('Update Ozon price', json_decode($result->fetchOne(), true));
    }

}
