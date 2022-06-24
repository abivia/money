<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class AddTest extends TestCase
{
    public function testAddNullPrecision()
    {
        bcscale(2);
        Money::setScale(4);
        $sum = Money::make('3.14')->add('0.00159265');
        $this->assertEquals('3.1416', $sum->value);
        $this->assertEquals(4, $sum->scale);
    }

    public function testAddNullPrecisionNoRounding()
    {
        bcscale(2);
        Money::setScale(4);
        $sum = Money::make('3.14')->add('0.00159265', null, false);
        $this->assertEquals('3.1415', $sum->value);
        $this->assertEquals(4, $sum->scale);
    }

    public function testAddLargePrecision()
    {
        bcscale(2);
        Money::setScale(4);
        $sum = Money::make('3.14')->add('0.00159265', 8);
        $this->assertEquals('3.14159265', $sum->value);
        $this->assertEquals(8, $sum->scale);
    }

    public function testAddSmallPrecision()
    {
        bcscale(2);
        Money::setScale(4);
        $sum = Money::make('3.14', 3)->add('0.00159265', 3);
        $this->assertEquals('3.142', $sum->value);
        $this->assertEquals(3, $sum->scale);
    }

}
