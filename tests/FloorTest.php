<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class FloorTest extends TestCase
{
    public function testFloorNegative()
    {
        $result = Money::make('-35.00')->floor();
        $this->assertEquals('-35', $result->value);
        $result = Money::make('-35.04')->floor();
        $this->assertEquals('-35', $result->value);
        $result = Money::make('-35.94')->floor();
        $this->assertEquals('-35', $result->value);
    }

    public function testFloorPositive()
    {
        $result = Money::make('35.00')->floor();
        $this->assertEquals('35', $result->value);
        $result = Money::make('35.04')->floor();
        $this->assertEquals('35', $result->value);
        $result = Money::make('35.94')->floor();
        $this->assertEquals('35', $result->value);
    }

    public function testFloorZero()
    {
        $result = Money::make('0.0')->floor();
        $this->assertEquals('0', $result->value);
        $result = Money::make('-0.0')->floor();
        $this->assertEquals('0', $result->value);
    }

}
