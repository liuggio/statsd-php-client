<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\StatsdClientInterface;

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


    public function __construct(StatsdClientInterface $client, $packetSize = null)
    {
        $this->client = $client;
        if (null !== $packetSize) {
            self::$packetSize = $packetSize;
        }
    }

    /**
     * This function reduces the number of packets,the reduced has the maximum dimension of self::MAX_UDP_SIZE_STR
     * Reference:
     * https://github.com/etsy/statsd/blob/master/README.md
     * All metrics can also be batch send in a single UDP packet, separated by a newline character.
     *
     * @param array $result
     * @param array $item
     *
     * @return array
     */
    private static function doReduce($result, $item)
    {
        $oldLastItem = array_pop($result);
        $sizeResult  = strlen($oldLastItem);
        $message     = $item;
        $totalSize   = $sizeResult + strlen($message) + 1; //the newline is the 1

        if (self::$packetSize < $totalSize) {
            //going to build another one
            array_push($result, $oldLastItem);
            array_push($result, $message);
        } else {
            //going to modifying the existing
            $separator = '';
            if ($sizeResult > 0) {
                $separator = PHP_EOL;
            }
            $oldLastItem = sprintf("%s%s%s", $oldLastItem, $separator, $message);
            array_push($result, $oldLastItem);
        }

        return $result;
    }

    /*
    * Send the metrics over the socket.
    *
    * {@inheritDoc}
    */
    public function send($data, $sampleRate = 1)
    {
        $data = $this->normalizeData($data);

        if (is_array($data)) {
            $data = array_reduce($data,"self::doReduce", array());
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
            $data = array($data);
        }
        if (!is_array($data) || empty($data)) {
            return;
        }

        return $data;
    }

}