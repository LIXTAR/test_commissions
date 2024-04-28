<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\BinData;
use App\Exception\BinDataCannotBeAcquiredException;

interface BinDataProviderInterface
{
    /**
     * @throws BinDataCannotBeAcquiredException
     */
    public function get(string $bin): BinData;
}
