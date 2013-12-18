<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\StatsdClient;
use Liuggio\StatsdClient\Entity\StatsdData;

class StatsdClientTest extends \PHPUnit_Framework_TestCase
{

    public function mockSenderWithAssertionOnWrite($messageToAssert)
    {
        $mock = $this->getMockBuilder('\Liuggio\StatsdClient\Sender\SocketSender') ->disableOriginalConstructor() ->getMock();

        $phpUnit = $this;
        $mock->expects($this->any())
            ->method('open')
            ->will($this->returnValue(true));

        // if the input is an array expects a call foreach item
        if (is_array($messageToAssert)) {
            $index = 0;
            foreach ($messageToAssert as $oneMessage) {
                $index++;
                $mock->expects($this->at($index))
                    ->method('write')
                    ->with($this->anything(),
                           $this->equalTo($oneMessage)
                    );
            }
        } elseif (null !== $messageToAssert) {
            // if the input is a string expects only once
            $mock->expects($this->once())
                ->method('write')
                ->with($this->anything(),
                    $this->equalTo($messageToAssert)
                );
        }

        return $mock;
    }

    public function mockStatsdClientWithAssertionOnWrite($messageToAssert)
    {
        $mockSender = $this->mockSenderWithAssertionOnWrite($messageToAssert);

        $statsdClient = new StatsdClient($mockSender, null, false);

        return $statsdClient;
    }

    public function mockFactory()
    {
        $mock =  $this->getMock('\Liuggio\StatsdClient\Factory\StatsdDataFactory', array('timing'));

        $statsData = new StatsdData();
        $statsData->setKey('key');
        $statsData->setValue('1');
        $statsData->setMetric('ms');

        $mock->expects($this->any())
            ->method('timing')
            ->will($this->returnValue($statsData));

        return $mock;
    }

    public static function provider()
    {
        /**
         * First
         */
        $statsData0 = new StatsdData();
        $statsData0->setKey('keyTiming');
        $statsData0->setValue('1');
        $statsData0->setMetric('ms');
        /**
         * Second
         */
        $stats1 = array();
        $statsData1 = new StatsdData();
        $statsData1->setKey('keyTiming');
        $statsData1->setValue('1');
        $statsData1->setMetric('ms');
        $stats1[] = $statsData1;

        $statsData1 = new StatsdData();
        $statsData1->setKey('keyIncrement');
        $statsData1->setValue('1');
        $statsData1->setMetric('c');
        $stats1[] = $statsData1;

        return array(
            array($statsData0, "keyTiming:1|ms"),
            array($stats1, array("keyTiming:1|ms", "keyIncrement:1|c")),
        );
    }

    public static function providerSend()
    {
        return array(
            array(array('gauge:value|g'), 'gauge:value|g'),
            array(array("keyTiming:1|ms", "keyIncrement:1|c"), array("keyTiming:1|ms", "keyIncrement:1|c")),
        );
    }

    /**
     * @dataProvider provider
     */
    public function testPrepareAndSend($statsdInput, $assertion)
    {
        $statsdMock = $this->mockStatsdClientWithAssertionOnWrite($assertion);
        $statsdMock->send($statsdInput);
    }

    /**
     * @dataProvider providerSend
     */
    public function testSend($array, $assertion)
    {
        $statsdMock = $this->mockStatsdClientWithAssertionOnWrite($assertion);
        $statsdMock->send($array);
    }
}
