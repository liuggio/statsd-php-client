<?php

namespace Liuggio\StatsdClient;

Interface StatsdClientInterface
{
    /**
     * @deprecated see PacketReducer constant
     */
    const MAX_UDP_SIZE_STR = 512;

    /*
     * Send the metrics
     *
     * @abstract
     * @param array|string|\Liuggio\StatsdClient\Sender\StatsdDataInterface $data       message(s) to sent
     * @param int                                                           $sampleRate Tells StatsD that this counter is being sent sampled every Xth of the time.
     *
     * @return integer the data sent in bytes
     */
    public function send($data, $sampleRate = 1);
}
