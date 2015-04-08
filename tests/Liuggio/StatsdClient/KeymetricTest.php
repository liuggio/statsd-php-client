<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\Sender\SysLogSender;
use Liuggio\StatsdClient\StatsdClient,
    Liuggio\StatsdClient\Factory\StatsdDataFactory,
    Liuggio\StatsdClient\Sender\SocketSender,
    Liuggio\StatsdClient\Service\StatsdService;

class KeymetricTest extends \PHPUnit_Framework_TestCase
{
    private $sender;
    private $client;
    private $factory;

    protected function setUp()
    {
        $this->sender = new SysLogSender();
        $this->client = new StatsdClient($this->sender);
        $this->factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');
    }

    public function testMetrickey()
    {
        // First test
        $service = new StatsDService($this->client, $this->factory);

        $service->setPrefix('hostname');
        $service->timing('usageTime', 100);
        $service->increment('visitor');
        $service->decrement('click');
        $service->gauge('gaugor', 333);
        $service->set('uniques', 765);

        $service->flush();
        unset($service);

        // Second test
        $service = new StatsDService($this->client, $this->factory);
        $service->setPrefix(null);
        $service->setSuffix('hostname');
        $service->timing('vhost.usageTime', 100);
        $service->increment('vhost.visitor');
        $service->decrement('vhost.click');
        $service->gauge('vhost.gaugor', 333);
        $service->set('vhost.uniques', 765);

        $service->flush();

        $service->timing('vhost.gaugor.hostname',800)->flush();
    }
}