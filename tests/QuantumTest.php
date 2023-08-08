<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class QuantumTest extends TestCase
{
    public function testQuantum()
    {
        $result = Money::quantum(0);
        $this->assertEquals('1', $result->value);
        $this->assertEquals(0, $result->scale);

        $result = Money::quantum(2);
        $this->assertEquals('0.01', $result->value);
        $this->assertEquals(2, $result->scale);

        $result = Money::quantum(5);
        $this->assertEquals('0.00001', $result->value);
        $this->assertEquals(5, $result->scale);
    }
}
