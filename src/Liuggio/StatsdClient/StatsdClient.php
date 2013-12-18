<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\Sender\SenderInterface;
use Liuggio\StatsdClient\Entity\StatsdDataInterface;

class StatsdClient implements StatsdClientInterface
{
    /**
     * @var boolean
     */
    private $failSilently;

    /**
     * @var SenderInterface
     */
    private $sender;

    /**
     * Constructor.
     *
     * @param SenderInterface $sender
     * @param boolean         $fail_silently
     */
    public function __construct(SenderInterface $sender, $fail_silently = true)
    {
        $this->sender       = $sender;
        $this->failSilently = $fail_silently;
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

        // add sampling
        if ($sampleRate < 1) {
            $data = $this->appendSampleRate($data, $sampleRate);
        }
        //failures in any of this should be silently ignored if ..
        try {
            $fp = $this->getSender()->open();
            if (!$fp) {
                return;
            }
            $written = 0;
            foreach ($data as $key => $message) {
                $written += $this->getSender()->write($fp, $message);
            }
            $this->getSender()->close($fp);
        } catch (\Exception $e) {
            $this->throwException($e);
        }

        return $written;
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
    private function getFailSilently()
    {
        return $this->failSilently;
    }

    /**
     * @return SenderInterface
     */
    private function getSender()
    {
        return $this->sender;
    }

    /**
     *  Reference: https://github.com/etsy/statsd/blob/master/README.md
     *  Sampling 0.1
     *  Tells StatsD that this counter is being sent sampled every 1/10th of the time.
     *
     * @param     $data
     * @param int $sampleRate
     *
     * @return array
     */
    private function appendSampleRate($data, $sampleRate = 1)
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

    /**
     * Throws an exception only if failSilently is false.
     *
     * @param \Exception $exception
     *
     * @throws \Exception
     */
    private function throwException(\Exception $exception)
    {
        if (!$this->getFailSilently()) {
            throw $exception;
        }
    }
}
