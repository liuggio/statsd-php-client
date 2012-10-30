<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\StatsdClient;
use Liuggio\StatsdClient\StatsdDataFactory;
use Liuggio\StatsdClient\Service\Sender;

class ReadmeTest extends \PHPUnit_Framework_TestCase
{
    public function testFullUsage() {

        // init the client
        // in your php script: $sender = new Sender();
        $sender =  $this->getMock('\\Liuggio\\StatsdClient\\Service\\SenderInterface', array('open', 'write', 'close'));
        $sender->expects($this->once())
            ->method('open')
            ->will($this->returnValue(true));

        $sender->expects($this->any())  //If you set the reduce = true into the StatsdClient the write will be called once
            ->method('write')
            ->will($this->returnValue(true));

        $sender->expects($this->once())
            ->method('close')
            ->will($this->returnValue(true));


        $client = new StatsdClient($sender);

        $factory = new StatsdDataFactory('\\Liuggio\\StatsdClient\\Entity\\StatsdData');

        // create the data with the factory
        $data[] = $factory->timing('usageTime', 100);
        $data[] = $factory->increment('visitor');
        $data[] = $factory->decrement('click');
        $data[] = $factory->gauge('gaugor', 333);
        $data[] = $factory->set('uniques', 765);

        // send the data as array or directly as object
        $client->send($data);
    }

}