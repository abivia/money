<?php

use Abivia\Money\Money;
use PHPUnit\Framework\TestCase;

class PowTest extends TestCase
{
    public function testPow()
    {
        bcscale(2);
        $result = Money::make('100.00')->pow('2.0');
        $this->assertEquals('10000.00', $result->value);
    }

    public function testPowRounding()
    {
        bcscale(2);
        $result = Money::make('0.5')->pow('3');
        $this->assertEquals('0.13', $result->value);
    }

    public function testPowTruncating()
    {
        bcscale(2);
        $result = Money::make('0.5')->pow('3', 0, false);
        $this->assertEquals('0.12', $result->value);
    }

}
