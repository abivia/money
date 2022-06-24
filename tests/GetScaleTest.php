<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class GetScaleTest extends TestCase
{
    public function testExplicit()
    {
        bcscale(4);
        $result = Money::make('35.04', 2)->getScale();
        $this->assertEquals(2, $result);
    }

    public function testImplicit()
    {
        bcscale(4);
        $result = Money::make('35.04')->getScale();
        $this->assertEquals(4, $result);
    }

}
