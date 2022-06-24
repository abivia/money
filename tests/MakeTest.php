<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class MakeTest extends TestCase
{
    public function testMake()
    {
        bcscale(2);
        $result = Money::make('35.04');
        $this->assertEquals('35.04', $result->value);
    }

    public function testMakeBad1()
    {
        $this->expectException(ValueError::class);
        Money::make('12foo.65');
    }

    public function testMakeBad2()
    {
        $this->expectException(ValueError::class);
        Money::make('foo0.65');
    }

    public function testMakeBad3()
    {
        $this->expectException(ValueError::class);
        Money::make('12.65foo');
    }

    public function testMakeImplicitScale()
    {
        bcscale(4);
        $result = Money::make('35.04');
        $this->assertEquals('35.0400', $result->value);
    }

    public function testMakeNegative()
    {
        $result = Money::make('-35.0451', 2);
        $this->assertEquals('-35.05', $result->value);
    }

    public function testMakePositive()
    {
        $result = Money::make('+35.0451', 2);
        $this->assertEquals('35.05', $result->value);
    }

    public function testMakeRounded()
    {
        $result = Money::make('35.0451', 2);
        $this->assertEquals('35.05', $result->value);
    }

    public function testToString()
    {
        Money::setScale(2);
        $result = (string) Money::make('35.04')->div('1.15');
        $this->assertEquals('30.47', $result);
    }

}
