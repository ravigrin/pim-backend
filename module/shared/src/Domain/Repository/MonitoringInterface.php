<?php

namespace Shared\Domain\Repository;

interface MonitoringInterface
{
    /**
     * @return string[]
     */
    public function findErrors(): array;

}
