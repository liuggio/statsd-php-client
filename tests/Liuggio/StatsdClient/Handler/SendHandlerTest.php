<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\Handler\BufferHandler;

class SendHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnCloseShouldEmptyTheBuffer()
    {
        $client = $this->getMock('\Liuggio\StatsdClient\StatsdClientInterface');
        $factory = $this->getMock('\Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface');
        $bufferHandler = new BufferHandler($client, $factory);

        $key = 'key';
        $data = array($key=>'1');

        $factory->expects($this->once())
            ->method('increment')
            ->with($this->equalTo($key))
            ->will($this->returnValue($data));

        $bufferHandler->increment($key);

        $client->expects($this->at(0))
            ->method('send')
            ->with($this->equalTo(array($data)));
        $bufferHandler->send();

        $client->expects($this->at(1))
            ->method('send')
            ->with($this->equalTo(array()));

        $bufferHandler->close();
    }

    public function testOnSendShouldEmptyTheBuffer()
    {
        $client = $this->getMock('\Liuggio\StatsdClient\StatsdClientInterface');
        $factory = $this->getMock('\Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface');
        $bufferHandler = new BufferHandler($client, $factory);

        $key = 'key';
        $data = array($key=>'1');

        $factory->expects($this->once())
            ->method('increment')
            ->with($this->equalTo($key))
            ->will($this->returnValue($data));

        $bufferHandler->increment($key);

        $client->expects($this->at(0))
            ->method('send')
            ->with($this->equalTo(array($data)));
        $bufferHandler->send();

        $client->expects($this->at(1))
            ->method('send')
            ->with($this->equalTo(array()));

        $bufferHandler->send();
    }

}
