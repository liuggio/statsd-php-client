<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\StatsdClientInterface;
use Liuggio\StatsdClient\Entity\StatsdDataInterface;

/**
 * This is a decorator the StatsdClientInterface
 * Class Reducer
 *
 * @package Liuggio\StatsdClient\Reducer
 */
class PacketReducer implements StatsdClientInterface
{
    /**
     * Jumbo frames can make use of this feature much more efficient.
     */
    const MAX_UDP_SIZE_GIGABIT = 8932;
    /**
     *  This is most likely for Intranets.
     */
    const MAX_UDP_SIZE_INTRANET = 1432;
    /**
     * If you are routing over the internet a value
     * in this range will be reasonable.
     * You might be able to go higher,
     * but you are at the mercy of all the hops in your route.
     */
    const MAX_UDP_SIZE_COMMODITY = 512;

    /**
     * @var StatsdClientInterface
     */
    private $client;

    /**
     * @var int
     */
    private static $packetSize = self::MAX_UDP_SIZE_COMMODITY;

    public function __construct(StatsdClientInterface $client, $packetSize = self::MAX_UDP_SIZE_COMMODITY)
    {
        $this->client = $client;
        self::$packetSize = $packetSize;
    }

    /**
     * This function reduces the number of packets,the reduced has the maximum dimension of self::MAX_UDP_SIZE_STR
     * Reference:
     * https://github.com/etsy/statsd/blob/master/README.md
     * All metrics can also be batch send in a single UDP packet, separated by a newline character.
     *
     * @param array $reducedMetrics
     * @param array $metric
     *
     * @return array
     */
    private static function doReduce($reducedMetrics, $metric)
    {
        $metricLength = strlen($metric);
        $lastReducedMetric = count($reducedMetrics) > 0 ? end($reducedMetrics) : null;

        if ($metricLength >= self::$packetSize
            || null === $lastReducedMetric
            || strlen($newMetric = $lastReducedMetric . "\n" . $metric) > self::$packetSize
        ) {
            $reducedMetrics[] = $metric;
        } else {
            array_pop($reducedMetrics);
            $reducedMetrics[] = $newMetric;
        }

        return $reducedMetrics;
    }

    /*
    * Send the metrics over the socket.
    *
    * {@inheritDoc}
    */
    public function send($data, $sampleRate = 1)
    {
        if (null===$data || (is_array($data) && count($data)==0)) {
            return 0;
        }

        $data = $this->normalizeData($data);
        if (is_array($data)) {
            $data = array_reduce($data, "self::doReduce", array());
        }

        return $this->client->send($data, $sampleRate);
    }

    /**
     * Transform all the data given into an array.
     *
     * @param $data
     *
     * @return array
     */
    private function normalizeData($data)
    {
        // check format
        if ($data instanceof StatsdDataInterface || is_string($data)) {
            return array($data);
        }
        if (is_array($data) && !empty($data)) {
            return $data;
        }

        return null;
    }
}
