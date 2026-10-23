<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

/**
 * Сущность/ресурс не найдены — мапится в HTTP 404.
 */
class NotFoundException extends RuntimeException
{
}
