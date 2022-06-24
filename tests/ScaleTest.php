<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class ScaleTest extends TestCase
{
    public function testScaleUp()
    {
        bcscale(2);
        $result = Money::make('123.45')->scale(5);
        $this->assertEquals('123.45000', $result->value);
    }

    public function testScaleRound()
    {
        bcscale(5);
        $result = Money::make('123.45678')->scale(3);
        $this->assertEquals('123.457', $result->value);
    }

    public function testScaleNoRound()
    {
        bcscale(5);
        $result = Money::make('123.45678')->scale(3, false);
        $this->assertEquals('123.456', $result->value);
    }

}
