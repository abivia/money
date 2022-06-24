<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class CeilTest extends TestCase
{
    public function testCeilNegative()
    {
        bcscale(2);
        $result = Money::make('-35.00')->ceil();
        $this->assertEquals('-35', $result->value);
        $result = Money::make('-35.04')->ceil();
        $this->assertEquals('-36', $result->value);
        $result = Money::make('-35.94')->ceil();
        $this->assertEquals('-36', $result->value);
    }

    public function testCeilPositive()
    {
        $result = Money::make('35.00')->ceil();
        $this->assertEquals('35', $result->value);
        $result = Money::make('35.04')->ceil();
        $this->assertEquals('36', $result->value);
        $result = Money::make('35.94')->ceil();
        $this->assertEquals('36', $result->value);
    }

    public function testCeilZero()
    {
        $result = Money::make('0.0')->ceil();
        $this->assertEquals('0', $result->value);
        $result = Money::make('-0.0')->ceil();
        $this->assertEquals('0', $result->value);
    }

}
