<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\Service\SenderInterface;
use Liuggio\StatsdClient\Model\StatsdDataInterface;
use Liuggio\StatsdClient\Exception\InvalidArgumentException;

class StatsdClient
{

    const MAX_UDP_SIZE_STR = 548;

    /**
     * @var string
     */
    private $host;
    /**
     * @var int
     */
    private $port;

    /**
     * @var
     */
    private $protocol;

    /**
     * @var boolean
     */
    private $failSilently;

    /**
     * @var Service\SenderInterface
     */
    private $sender;

    /**
     * @param $host
     * @param $port
     * @param $protocol
     * @param Service\SenderInterface $sender
     * @param bool $fail_silently
     */
    public function __construct($host, $port, $protocol, SenderInterface $sender, $fail_silently = true)
    {
        $this->host = $host;
        $this->port = $port;
        $this->sender = $sender;
        $this->failSilently = $fail_silently;
    }

    private function throwException(\Exception $exception) {
            if (!$this->getFailSilently()) {
                throw $exception;
            }
    }

    function doReduce($result, $item)
    {
        $oldLastItem = array_pop($result);
        $sizeResult = strlen($oldLastItem);
        $message = $item;
        $totalSize = $sizeResult + strlen($message) + 1; //the comma is the 1

        if (self::MAX_UDP_SIZE_STR < $totalSize) {
            //going to build another one
            array_push($result, $message);
            array_push($result, $oldLastItem);
        } else {
            //going to modifying the existing
            $separator= '';
            if ($sizeResult > 0) {
                $separator= PHP_EOL;
            }
            $oldLastItem = sprintf("%s%s%s", $oldLastItem, $separator, $message);
            array_push($result, $oldLastItem);
        }
        return $result;
    }

    /**
     * this function reduce the amount of data that should be send with the same message
     * @param $arrayData
     */
    public function reduceCount($arrayData)
    {
        if (is_array($arrayData)) {
            $arrayData = array_reduce($arrayData, "self::doReduce", array());
        }
        return $arrayData;
    }

    /**
     * Updates one or more stats.
     *
     * @param $stats array of StatsdDataInterface
     * @param int $rate
     */
    public function prepareAndSend($stats, $rate = 1)
    {
        if (!is_array($stats)) {
            if ($stats instanceof StatsdDataInterface) {
                $stats = array($stats);
            } else {
                $this->throwException(new InvalidArgumentException());
            }
        }
        $data = array();
        foreach ($stats as $stat) {
            if ($stat instanceof StatsdDataInterface) {
                $data[$stat->getKey()] = $stat->getMessage();
            } else {
                $this->throwException(new InvalidArgumentException());
            }
        }
        $this->send($data, $rate);
    }

    /**
     *
     * @param $data
     * @param int $sampleRate
     */
    public function appendSampleRate($data, $sampleRate = 1)
    {
        $sampledData = array();
        if ($sampleRate < 1) {
            foreach ($data as $key => $message) {
                $sampledData[$key] = sprintf('%s|@%s' . $message . $sampleRate);
            }
            $data = $sampledData;
        }
        return $data;
    }
    /*
     * Squirt the metrics over UDP
     *
     * @param array $data Array of messages to sent
     * @param int $sampleRate Tells StatsD that this counter is being sent sampled every Xth of the time.
     */
    public function send($data, $sampleRate = 1, $reduceData = false)
    {
        if (!is_array($data) || empty($data)) {
            return;
        }
        // add sampling
        if ($sampleRate < 1) {
            $data = $this->appendSampleRate($data, $sampleRate);
        }

        if ($reduceData) {
            $data = $this->reduceCount($data);
        }

        // Wrap this in a try/catch - failures in any of this should be silently ignored
        try {
            $host = $this->getHost();
            $port = $this->getPort();
            $protocol = $this->getProtocol();
            // php://temp
            $errno = 0;
            $errstr = '';
            $fp = $this->getSender()->open($protocol, $host, $port, $errno, $errstr);

            if (!$fp) {
                return;
            }
            foreach ($data as $key => $message) {
                $this->getSender()->write($fp, $message);
            }
            $this->getSender()->close($fp);
        } catch (\Exception $e) {
            $this->throwException($e);
        }
    }


    /**
     * @param boolean $failSilently
     */
    public function setFailSilently($failSilently)
    {
        $this->failSilently = $failSilently;
    }

    /**
     * @return boolean
     */
    public function getFailSilently()
    {
        return $this->failSilently;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param  $protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @return
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param \Liuggio\StatsdClient\Service\SenderInterface $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return \Liuggio\StatsdClient\Service\SenderInterface
     */
    public function getSender()
    {
        return $this->sender;
    }


}
