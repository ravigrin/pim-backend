<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine\Dwh;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Pim\Domain\Repository\Dwh\ProductInterface;
use Psr\Log\LoggerInterface;

class ProductRepository implements ProductInterface
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
    public function findAll(): array
    {
        $sql = <<<SQL
        select Справочник_Номенклатура.Артикул           as vendorCode
             , Справочник_Номенклатура.Наименование      as productName
             , Справочник_Марки.Наименование             as brandName
             , Справочник_Номенклатура.Номенклатура_guid as productGuid
             , Справочник_Марки.Марки_guid               as brandGuid
             , Справочник_ВидыНоменклатуры.Наименование  as type
        from dwh.ka.Справочник_Номенклатура
                 left join dwh.ka.Справочник_ВидыНоменклатуры
                           on Справочник_ВидыНоменклатуры.ВидыНоменклатуры_NID = Справочник_Номенклатура.ВидНоменклатуры_NID
                 left join dwh.ka.Справочник_Марки on Справочник_Марки.Марки_NID = Справочник_Номенклатура.Марка_NID
        where Справочник_Номенклатура.ЭтоГруппа = 0
          and (
              Справочник_ВидыНоменклатуры.Наименование = N'Продукция' or 
              Справочник_ВидыНоменклатуры.Наименование = N'Комплект'
              )
          and Справочник_Номенклатура.Артикул is not null
          and Справочник_Номенклатура.ПометкаУдаления = 0;
        SQL;
        return $this->connection->executeQuery($sql)->fetchAllAssociative();
    }

}
