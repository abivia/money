<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class CompTest extends TestCase
{
    public function testCompLess()
    {
        $result = Money::make('35.04')->comp('100');
        $this->assertEquals(-1, $result);
    }

    public function testCompEqual()
    {
        $result = Money::make('6.50')->comp('6.50');
        $this->assertEquals(0, $result);
    }

    public function testCompEqualPastScale()
    {
        bcscale(2);
        $result = Money::make('6.504')->comp('6.50');
        $this->assertEquals(0, $result);
    }

    public function testCompEqualNegativeZero()
    {
        $result = Money::make('-0')->comp('0.00');
        $this->assertEquals(0, $result);
    }

    public function testCompGreater()
    {
        $result = Money::make('35.04')->comp('-10');
        $this->assertEquals(1, $result);
    }

}
