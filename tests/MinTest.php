<?php

use PHPUnit\Framework\TestCase;
use Abivia\Money\Money;

class MinTest extends TestCase
{
    public function testMinNone()
    {
        $result = Money::min();
        $this->assertNull($result);
    }

    public function testMinOne()
    {
        bcscale(4);
        $result = Money::min('123.456');
        $this->assertEquals('123.4560', $result->value);
    }

    public function testMinMany()
    {
        bcscale(4);
        $result = Money::min('123.456', Money::make('256'), '75');
        $this->assertEquals('75.0000', $result->value);
    }

    public function testMinNested()
    {
        bcscale(4);
        $result = Money::min('123.456', [Money::make('256'), '75'], '512.5', '75');
        $this->assertEquals('75.0000', $result->value);
    }

}
