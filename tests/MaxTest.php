<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class MaxTest extends TestCase
{
    public function testMaxNone()
    {
        $result = Money::max();
        $this->assertNull($result);
    }

    public function testMaxOne()
    {
        bcscale(4);
        $result = Money::max('123.456');
        $this->assertEquals('123.4560', $result->value);
    }

    public function testMaxMany()
    {
        bcscale(4);
        $result = Money::max('123.456', Money::make('256'), '75');
        $this->assertEquals('256.0000', $result->value);
    }

    public function testMaxNested()
    {
        bcscale(4);
        $result = Money::max('123.456', [Money::make('256'), '75'], '512.5', '75');
        $this->assertEquals('512.5000', $result->value);
    }

}
