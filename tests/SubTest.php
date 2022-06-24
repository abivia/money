<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class SubTest extends TestCase
{
    public function testSubNullPrecision()
    {
        bcscale(2);
        Money::setScale(4);
        $sum = Money::make('3.14')->sub('0.00159265');
        $this->assertEquals('3.1384', $sum->value);
        $this->assertEquals(4, $sum->scale);
    }

    public function testSubNullPrecisionNoRounding()
    {
        bcscale(2);
        Money::setScale(4);
        $sum = Money::make('3.14')->sub('0.00159265', null, false);
        $this->assertEquals('3.1385', $sum->value);
        $this->assertEquals(4, $sum->scale);
    }

    public function testSubLargePrecision()
    {
        bcscale(2);
        Money::setScale(4);
        $sum = Money::make('3.14')->sub('0.00159265', 8);
        $this->assertEquals('3.13840735', $sum->value);
        $this->assertEquals(8, $sum->scale);
    }

    public function testSubSmallPrecision()
    {
        bcscale(2);
        Money::setScale(4);
        $sum = Money::make('3.14', 3)->sub('0.00159265', 3);
        $this->assertEquals('3.138', $sum->value);
        $this->assertEquals(3, $sum->scale);
    }

}
