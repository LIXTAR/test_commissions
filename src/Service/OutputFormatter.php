<?php

declare(strict_types=1);

namespace App\Service;

readonly class OutputFormatter
{
    public function format(float $number): string
    {
        return number_format(ceil($number * 100) / 100, 2, '.', '');
    }
}
