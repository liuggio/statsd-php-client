<?php

namespace Liuggio\StatsdClient\Factory;

use Liuggio\StatsdClient\Factory\StatsdDataFactory;

class StatsDataFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $statsDataFactory;

    public function setUp()
    {
        $this->statsDataFactory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');
    }

    public function testTiming()
    {
        $key = 'key';
        $value = microtime();
        $valueFloat = (string) floatval($value);

        $obj = $this->statsDataFactory->timing($key, $value);
        $this->assertEquals($key, $obj->getKey());
        $this->assertContains($valueFloat, $obj->getValue());
        $this->assertContains('ms', $obj->getMetric());
    }

    public function testDecrement()
    {
        $key = 'key';
        $value = -1;
        $stringValue = intval($value);

        $obj = $this->statsDataFactory->decrement($key);
        $this->assertEquals($key, $obj->getKey());
        $this->assertEquals($stringValue, $obj->getValue());
        $this->assertEquals('c', $obj->getMetric());
    }

    public function testGauge()
    {
        $key = 'key';
        $value = 1000;
        $stringValue = (string) intval($value);

        $obj = $this->statsDataFactory->gauge($key, $value);
        $this->assertEquals($key, $obj->getKey());
        $this->assertEquals($stringValue, $obj->getValue());
        $this->assertEquals('g', $obj->getMetric());
    }

    public function testIncrement()
    {
        $key = 'key';
        $value = 1;
        $stringValue = intval($value);

        $obj = $this->statsDataFactory->increment($key);
        $this->assertEquals($key, $obj->getKey());
        $this->assertEquals($stringValue, $obj->getValue());
        $this->assertEquals('c', $obj->getMetric());
    }

}
