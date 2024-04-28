<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Throwable;

class BinDataCannotBeAcquiredException extends RuntimeException implements Throwable
{
    public function __construct(
        string     $message = "Bin data cannot be acquired",
        int        $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
