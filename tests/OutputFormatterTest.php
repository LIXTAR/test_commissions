<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\OutputFormatter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OutputFormatterTest extends KernelTestCase
{
    private readonly OutputFormatter $formatter;

    protected function setUp(): void
    {
        $this->formatter = KernelTestCase::getContainer()->get(OutputFormatter::class);
    }

    public function testInt(): void
    {
        $this->assertEquals("1.00", $this->formatter->format(1));
    }

    public function testCeil(): void
    {
        $this->assertEquals("1.00", $this->formatter->format(0.991));
        $this->assertEquals("1.01", $this->formatter->format(1.001));
    }
}
