<?php

namespace Liuggio\StatsdClient;


use Liuggio\StatsdClient\StatsdDataFactory;


class StatsDataFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $statsDataFactory;

    public function setUp()
    {
        $this->statsDataFactory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');
    }

    public function testCreateStatsData()
    {
        $key = 'key';
        $value='val';

        $obj = $this->statsDataFactory->createStatsdData($key, $value);
        $this->assertEquals($key, $obj[0]->getKey());
        $this->assertEquals($value, $obj[0]->getValue());
    }

    public function testTiming()
    {
        $key = 'key';
        $value = microtime();
        $valueFloat = (string) floatval($value);

        $obj = $this->statsDataFactory->timing($key, $value);
        $this->assertEquals($key, $obj[0]->getKey());
        $this->assertContains($valueFloat, $obj[0]->getValue());
        $this->assertContains('ms', $obj[0]->getMetric());
    }

    public function testCreateStatsdData()
    {
        $key = 'key';
        $value = -1;
        $stringValue = intval($value);

        $obj = $this->statsDataFactory->createStatsdData($key, $value);
        $this->assertEquals($key, $obj[0]->getKey());
        $this->assertEquals($stringValue, $obj[0]->getValue());
        $this->assertEquals('c', $obj[0]->getMetric());
    }

    public function testGauge()
    {
        $key = 'key';
        $value = 1000;
        $stringValue = (string) intval($value);

        $obj = $this->statsDataFactory->gauge($key, $value);
        $this->assertEquals($key, $obj[0]->getKey());
        $this->assertEquals($stringValue, $obj[0]->getValue());
        $this->assertEquals('g', $obj[0]->getMetric());
    }

    public function testDecrement()
    {
        $key = 'key';
        $value = -1;
        $stringValue = intval($value);

        $obj = $this->statsDataFactory->decrement($key);
        $this->assertEquals($key, $obj[0]->getKey());
        $this->assertEquals($stringValue, $obj[0]->getValue());
        $this->assertEquals('c', $obj[0]->getMetric());
    }

    public function testcreateStatsdDataIncrement()
    {
        $key = 'key';
        $value = 1;
        $stringValue = intval($value);

        $obj = $this->statsDataFactory->increment($key);
        $this->assertEquals($key, $obj[0]->getKey());
        $this->assertEquals($stringValue, $obj[0]->getValue());
        $this->assertEquals('c', $obj[0]->getMetric());
    }

//    public function testAddSampling()
//    {
//        $key = 'key';
//        $value = 1;
//
//        $obj = new \Liuggio\StatsdClient\Entity\StatsdData();
//        $obj->setKey($key);
//        $obj->setValue('1');
//        $obj->setMetric('c');
//
//
//        $float = 0.1;
//        $obj2 = $this->statsDataFactory->addSampling($obj, $float);
//        $this->assertEquals($key, $obj2->getKey());
//        $this->assertEquals('1|c|@0.10', $obj2->getValue());
//    }
}
