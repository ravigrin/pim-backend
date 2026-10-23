<?php

namespace Pim\Domain\Repository\Pim;

interface ChannelInterface
{
    /**
     * @param string $userID
     */
    public function findByUser(string $userID): array;

}
