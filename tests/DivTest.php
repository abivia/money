<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class DivTest extends TestCase
{
    public function testDivRounding()
    {
        bcscale(2);
        $result = Money::make('35.04')->div('1.15');
        $this->assertEquals('30.47', $result->value);
        $this->assertEquals(2, $result->scale);
    }

    public function testDivNoRounding()
    {
        bcscale(2);
        $result = Money::make('35.04')->div('1.15', 2,false);
        $this->assertEquals('30.46', $result->value);
        $this->assertEquals(2, $result->scale);
    }

}
