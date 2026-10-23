<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Shared\Domain\Repository\MonitoringInterface;

final readonly class MonitoringRepository implements MonitoringInterface
{
    private Connection $connection;

    public function __construct(
        private ManagerRegistry $doctrine,
    ) {
        /** @var Connection $dwh */
        $dwh = $this->doctrine->getConnection('dwh');
        $this->connection = $dwh;
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public function findErrors(): array
    {
        $sql = <<<SQL
            select msg from php_bot.v_monitoring
        SQL;

        $result = $this->connection->executeQuery($sql)->fetchAllAssociative();
        if (empty($result)) {
            return [];
        }
        /** @var string[] */
        return array_column($result, 'msg');
    }
}
