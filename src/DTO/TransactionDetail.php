<?php

declare(strict_types=1);

namespace App\DTO;

readonly class TransactionDetail
{
    public function __construct(
        public string $bin,
        public string $amount,
        public string $currency,
    ) {
    }
}
