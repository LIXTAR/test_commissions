<?php

declare(strict_types=1);

namespace App\Tests;

use App\DTO\TransactionDetail;
use App\Service\CommissionsCalculator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CommissionsCalculatorTest extends KernelTestCase
{
    private readonly CommissionsCalculator $commissionsCalculator;

    protected function setUp(): void
    {
        $this->commissionsCalculator = KernelTestCase::getContainer()->get(CommissionsCalculator::class);
    }

    public function testPositive(): void
    {
        $detail = new TransactionDetail(
            "45717360",
            "100.00",
            "EUR",
        );
        $this->assertEquals(1, $this->commissionsCalculator->calculate($detail));
    }
}
