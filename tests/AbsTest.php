<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class AbsTest extends TestCase
{
    public function testAbsNegative()
    {
        bcscale(2);
        $result = Money::make('-35.04')->abs();
        $this->assertEquals('35.04', $result->value);
    }

    public function testAbsNegativeZero()
    {
        bcscale(2);
        $result = Money::make('-0')->abs();
        $this->assertEquals('0.00', $result->value);
    }

    public function testAbsPositive()
    {
        bcscale(2);
        $result = Money::make('35.04')->abs();
        $this->assertEquals('35.04', $result->value);
    }

}
