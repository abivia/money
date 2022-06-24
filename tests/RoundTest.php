<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class RoundTest extends TestCase
{
    public function testRound1()
    {
        $result = Money::make('123.0045', 4)->round(3);
        $this->assertEquals('123.005', $result->value);
    }

    public function testRound2()
    {
        bcscale(2);
        $result = Money::make('123.4445', 4)->round(2);
        $this->assertEquals('123.44', $result->value);
    }

    public function testRound3()
    {
        bcscale(2);
        $result = Money::make('123.4445', 4)->round(0);
        $this->assertEquals('123', $result->value);
    }

    public function testRoundScaleZero()
    {
        $result = Money::make('4.57', 0);
        $this->assertEquals('5', $result->value);
    }

}
