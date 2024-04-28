<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Throwable;

class CommissionCannotBeCalculatedException extends RuntimeException implements Throwable
{
    public function __construct(
        string     $message = "Commission cannot be calculated",
        int        $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
