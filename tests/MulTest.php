<?php

use Abivia\Money\Money;
use PHPUnit\Framework\TestCase;

class MulTest extends TestCase
{
    public function testMul()
    {
        bcscale(2);
        $result = Money::make('100.00')->mul('-1.10');
        $this->assertEquals('-110.00', $result->value);
    }

    public function testMulRounding()
    {
        bcscale(2);
        $result = Money::make('123.45')->mul('0.10');
        $this->assertEquals('12.35', $result->value);
    }

    public function testMulTruncating()
    {
        bcscale(2);
        $result = Money::make('123.45')->mul('0.10', 2, false);
        $this->assertEquals('12.34', $result->value);
    }

}
