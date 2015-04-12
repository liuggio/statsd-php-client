<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\Factory\StatsdDataFactory,
    Liuggio\StatsdClient\Service\StatsdService;

class KeymetricTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Prefix and suffix key metric
     */
    public function testKeyMetric()
    {
        $client = $this->getMockBuilder('Liuggio\StatsdClient\StatsdClient')
            ->disableOriginalConstructor()
            ->getMock();
        $factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');

        $service = new StatsdService($client, $factory);

        // First - Prefix and suffix
        $service->setPrefix('prefix_hostname');
        $service->setSuffix('hostname_suffix');
        $service->timing('usageTime', 100);
        $service->increment('visitor');

        $this->assertSame('prefix_hostname.usageTime.hostname_suffix', $factory->produceStatsdData('usageTime')->getKey());
        $this->assertSame('prefix_hostname.visitor.hostname_suffix', $factory->produceStatsdData('visitor')->getKey());

        // Second - Prefix only
        $service->setSuffix(null);
        $service->decrement('click');

        $this->assertSame('prefix_hostname.click', $factory->produceStatsdData('click')->getKey());

        // Second - Suffix only
        $service->setSuffix('hostname_suffix');
        $service->setPrefix(null);
        $service->gauge('gaugor', 333);

        $this->assertSame('gaugor.hostname_suffix', $factory->produceStatsdData('gaugor')->getKey());

        // Third - Never Prefix and suffix
        $service->setPrefix(null);
        $service->setSuffix(null);
        $service->set('uniques', 765);

        $this->assertSame('uniques', $factory->produceStatsdData('uniques')->getKey());


        $service->flush();
    }

}