<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\PacketReducer;
use Liuggio\StatsdClient\Entity\StatsdData;

class PacketReducerTest extends \PHPUnit_Framework_TestCase
{
    public function getMockClient()
    {
        return $this->getMockBuilder('\Liuggio\StatsdClient\StatsdClientInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testReduceWithMaxUdpPacketSplitInTwoPacket()
    {
        $data = array();
        $msg = 'A3456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789';    //1
        $msg .= 'B23456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 '; //2
        $msg .= 'C23456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 '; //3
        $msg .= 'E23456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 '; //4
        $msg .= 'F23456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789|c'; //500
        $data[] = $msg;

        $msg = 'Bkey:';
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789|c';
        $data[] = $msg;

        $mock = $this->getMockClient();
        $mock->expects($this->any())
            ->method('send')
            ->with($this->equalTo($data));
        $reducer = new PacketReducer($mock);
        $reducer->send($data);
    }

    public function testReduceWithString()
    {
        $msg = 'A3456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789:';
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789|c';
        $data[] = $msg;

        $msg = 'B3456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789:';
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789|c';
        $data[] = $msg;

        $dataToAssert = array($data[0] . PHP_EOL . $data[1]);

        $mock = $this->getMockClient();
        $mock->expects($this->any())
            ->method('send')
            ->with($this->equalTo($dataToAssert));

        $reducer = new PacketReducer($mock);
        $reducer->send($data);
    }

    public function testReduceCount()
    {
        $entity0 = new StatsdData();
        $entity0->setKey('key1');
        $entity0->setValue('1');
        $entity0->setMetric('c');
        $data[] = $entity0;

        $entity0 = new StatsdData();
        $entity0->setKey('key2');
        $entity0->setValue('2');
        $entity0->setMetric('ms');
        $data[] = $entity0;

        $dataToAssert = array('key1:1|c' . PHP_EOL . 'key2:2|ms');

        $mock = $this->getMockClient();
        $mock->expects($this->any())
            ->method('send')
            ->with($this->equalTo($dataToAssert));

        $reducer = new PacketReducer($mock);
        $reducer->send($data);
    }
    public function testMultiplePacketsWithReducing()
    {
        $assertion = array();
        $msg = '23456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789';

        $data[] = 'A'.$msg;
        $assertion[0] = 'A'.$msg;
        $data[] = 'B'. $msg;
        $assertion[0] .= PHP_EOL . 'B'.$msg;
        $data[] = 'C'.$msg;
        $assertion[0] .= PHP_EOL . 'C'.$msg;
        $data[] = 'D'.$msg;
        $assertion[0] .= PHP_EOL . 'D'.$msg;
        $data[] = 'E'.$msg;
        $assertion[0] .=  PHP_EOL . 'E'.$msg;

        $data[] = 'F'.$msg;
        $assertion[1] = 'F'.$msg;
        $data[] = 'G'.$msg;
        $assertion[1] .= PHP_EOL . 'G'.$msg;
        $data[] = 'H'.$msg;
        $assertion[1] .= PHP_EOL . 'H'.$msg;
        $data[] = 'I'.$msg;
        $assertion[1] .= PHP_EOL . 'I'.$msg;
        $data[] = 'L'.$msg;
        $assertion[1] .= PHP_EOL . 'L'.$msg;

        $data[] = 'M'.$msg;
        $assertion[2] = 'M'.$msg;
        $data[] = 'N'.$msg;
        $assertion[2] .= PHP_EOL . 'N'.$msg;

        $mock = $this->getMockClient();
        $mock->expects($this->any())
            ->method('send')
            ->with($this->equalTo($assertion));
        $reducer = new PacketReducer($mock);

        $reducer->send($data);
    }

    public function testSingleStatsdData()
    {
        $data = new StatsdData();
        $data->setKey('key');
        $data->setMetric(\Liuggio\StatsdClient\Entity\StatsdDataInterface::STATSD_METRIC_TIMING);
        $data->setValue(2);

        $assertion = array($data);

        $mock = $this->getMockClient();
        $mock->expects($this->any())
            ->method('send')
            ->with($this->equalTo($assertion));
        $reducer = new PacketReducer($mock);

        $reducer->send($data);
    }
}
