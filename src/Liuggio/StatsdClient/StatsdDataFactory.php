<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\Model\StatsdDataInterface;



class StatsdDataFactory
{
    /**
     * @var \Liuggio\StatsdClient\Model\StatsdDataInterface
     */
    private $entityClass;

    public function __construct($entity_class)
    {
        $this->setEntityClass($entity_class);
    }

    /**
     * Sets one or more timing values
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $time The elapsed time (ms) to log
     **/
    public function timing($stats, $time) {
        return $this->createStatsdData($stats, $time, 1, 'ms');
    }

    /**
     * Sets one or more gauges to a value
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $value The value for the stats.
     **/
    public function gauge($stats, $value) {
        return $this->createStatsdData($stats, $value, 1, 'g');
    }

    /**
     * A "Set" is a count of unique events.
     * This data type acts like a counter, but supports counting
     * of unique occurences of values between flushes. The backend
     * receives the number of unique events that happened since
     * the last flush.
     *
     * The reference use case involved tracking the number of active
     * and logged in users by sending the current userId of a user
     * with each request with a key of "uniques" (or similar).
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $value The value for the stats.
     * @return array
     **/
    public function set($stats, $value) {
        return $this->createStatsdData($stats, $value, 1, 's');
    }

    /**
     * Increments one or more stats counters
     *
     * @param string|array $stats The metric(s) to increment.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @return array
     **/
    public function increment($stats, $sampleRate=1) {
        return $this->createStatsdData($stats, 1, $sampleRate, 'c');
    }

    /**
     * Decrements one or more stats counters.
     *
     * @param string|array $stats The metric(s) to decrement.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @return mixed
     **/
    public function decrement($stats, $sampleRate=1) {
        return $this->createStatsdData($stats, -1, $sampleRate, 'c');
    }

    /**
     * Updates one or more stats.
     *
     * @param string|array $stats The metric(s) to update. Should be either a string or array of metrics.
     * @param int|1 $delta The amount to increment/decrement each metric by.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @param string|c $metric The metric type ("c" for count, "ms" for timing, "g" for gauge, "s" for set)
     * @return array
     **/
    public function createStatsdData($stats, $val=1, $sampleRate=1, $metric='c') {
        if (!is_array($stats)) { $stats = array($stats); }
        $data = array();
        foreach($stats as $stat) {
            $StatsdData = $this->getEntityClass();
            $StatsdData = new $StatsdData();
            if (null !== $stat) {
                $StatsdData->setKey($stat);
            }
            if (null !== $val) {
                $StatsdData->setValue($val);
            }
            if (null !== $metric) {
                $StatsdData->setMetric($metric);
            }
            $data[] = $StatsdData;
        }
        return $data;
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
     * @param \Liuggio\StatsdClient\Model\StatsdDataInterface $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @return \Liuggio\StatsdClient\Model\StatsdDataInterface
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }


}
