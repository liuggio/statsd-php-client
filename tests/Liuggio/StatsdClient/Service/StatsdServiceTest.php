<?php

namespace Liuggio\StatsdClient\Entity;

use Liuggio\StatsdClient\Service\StatsdService;

class StatsdServiceTest extends \PHPUnit_Framework_TestCase
{
    private $clientMock;
    private $factoryMock;

    public function setUp()
    {
        $this->clientMock = $this->getMockBuilder('Liuggio\StatsdClient\StatsdClient')
            ->disableOriginalConstructor()
            ->getMock();
        $this->factoryMock = $this->getMockBuilder('Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testFactoryImplementation()
    {
        // Configure the client mock.
        $this->factoryMock->expects($this->once())->method('timing');
        $this->factoryMock->expects($this->once())->method('gauge');
        $this->factoryMock->expects($this->once())->method('set');
        $this->factoryMock->expects($this->once())->method('increment');
        $this->factoryMock->expects($this->once())->method('decrement');
        $this->factoryMock->expects($this->once())->method('updateCount');

        // Actual test
        $dut = new StatsdService($this->clientMock, $this->factoryMock);
        $dut->timing('foo.bar', 123);
        $dut->gauge('foo.bar', 123);
        $dut->set('foo.bar', 123);
        $dut->increment('foo.bar');
        $dut->decrement('foo.bar');
        $dut->updateCount('foo.bar', 123);
    }

    public function testFlush()
    {
        $data = new StatsdData();
        $this->factoryMock->expects($this->once())->method('timing')->willReturn($data);
        $this->clientMock->expects($this->once())->method('send')
            ->with($this->equalTo(array($data)));

        // Actual test
        $dut = new StatsdService($this->clientMock, $this->factoryMock);
        $dut->timing('foobar', 123);
        $dut->flush();
    }
}
