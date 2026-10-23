<?php

declare(strict_types=1);

namespace Shared\Application\Command\Monitoring;

use Psr\Log\LoggerInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\MonitoringInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private MonitoringInterface $monitoringRepository,
        private LoggerInterface     $logger,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $errors = $this->monitoringRepository->findErrors();
        if ($errors) {
            $json = json_encode(
                value: $errors,
                flags: JSON_UNESCAPED_UNICODE
            );
            if (is_string($json)) {
                $this->logger->info($json);
            }
        }

    }
}
