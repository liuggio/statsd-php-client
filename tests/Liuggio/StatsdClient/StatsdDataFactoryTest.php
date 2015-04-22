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

    public function testProduceStatsdData()
    {
        $key = 'key';
        $value='val';

        $obj = $this->statsDataFactory->produceStatsdData($key, $value);
        $this->assertEquals($key, $obj->getKey());
        $this->assertEquals($value, $obj->getValue());
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

    public function testProduceStatsdDataDecrement()
    {
        $key = 'key';
        $value = -1;
        $stringValue = intval($value);

        $obj = $this->statsDataFactory->produceStatsdData($key, $value);
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

    public function testcreateStatsdDataIncrement()
    {
        $key = 'key';
        $value = 1;
        $stringValue = intval($value);

        $obj = $this->statsDataFactory->increment($key);
        $this->assertEquals($key, $obj->getKey());
        $this->assertEquals($stringValue, $obj->getValue());
        $this->assertEquals('c', $obj->getMetric());
    }

    public function testCreateStatsdDataUpdateCount()
    {
        $key = 'key';
        $value = 10;
        $stringValue = intval($value);

        $obj = $this->statsDataFactory->updateCount($key, 10);
        $this->assertEquals($key, $obj->getKey());
        $this->assertEquals($stringValue, $obj->getValue());
        $this->assertEquals('c', $obj->getMetric());
    }

    /**
     * Prefix only
     */
    public function testKeyMetricWithPrefixOnly()
    {
        $key = 'gaugor';
        $value = 333;

        $this->statsDataFactory->setPrefix('prefix_hostname');
        $this->statsDataFactory->setSuffix(null);
        $obj = $this->statsDataFactory->gauge($key, $value);

        $this->assertEquals("prefix_hostname.{$key}", $obj->getKey());
        $this->assertEquals($value, $obj->getValue());
    }

    /**
     * Suffix only
     */
    public function testKeyMetricWithSuffixOnly()
    {
        $key = 'uniques';
        $value = 765;

        $this->statsDataFactory->setPrefix(null);
        $this->statsDataFactory->setSuffix('hostname_suffix');
        $obj = $this->statsDataFactory->set($key, $value);

        $this->assertEquals("{$key}.hostname_suffix", $obj->getKey());
        $this->assertEquals($value, $obj->getValue());
    }
}
