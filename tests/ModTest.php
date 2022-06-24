<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class ModTest extends TestCase
{
    public function testMod1()
    {
        bcscale(2);
        $result = Money::make('123.45')->mod('0.10');
        $this->assertEquals('0.05', $result->value);
    }

    public function testMod2()
    {
        bcscale(2);
        $result = Money::make('123.45')->mod('-0.10');
        $this->assertEquals('0.05', $result->value);
    }

    public function testMod3()
    {
        bcscale(2);
        $result = Money::make('-123.44')->mod('-0.10');
        $this->assertEquals('-0.04', $result->value);
    }

}
