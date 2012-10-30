<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\Entity\StatsdDataInterface;


class StatsdDataFactory
{
    /**
     * @var \Liuggio\StatsdClient\Entity\StatsdDataInterface
     */
    private $entityClass;

    public function __construct($entity_class)
    {
        $this->setEntityClass($entity_class);
    }

    /**
     * This function creates a 'timing' StatsdData
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $time The elapsed time (ms) to log
     **/
    public function timing($key, $time)
    {
        return $this->produceStatsdData($key, $time, StatsdDataInterface::STATSD_METRIC_TIMING);
    }

    /**
     * This function creates a 'gauge' StatsdData
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $value The value for the stats.
     **/
    public function gauge($key, $value)
    {
        return $this->produceStatsdData($key, $value, StatsdDataInterface::STATSD_METRIC_GAUGE);
    }

    /**
     * This function creates a 'set' StatsdData object
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
    public function set($key, $value)
    {
        return $this->produceStatsdData($key, $value, StatsdDataInterface::STATSD_METRIC_SET);
    }

    /**
     * This function creates a 'increment' StatsdData object
     *
     * @param string|array $key The metric(s) to increment.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @return array
     **/
    public function increment($key)
    {
        return $this->produceStatsdData($key, 1, StatsdDataInterface::STATSD_METRIC_COUNT);
    }

    /**
     * This function creates a 'decrement' StatsdData object.
     *
     * @param string|array $key The metric(s) to decrement.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @return mixed
     **/
    public function decrement($key)
    {
        return $this->produceStatsdData($key, -1, StatsdDataInterface::STATSD_METRIC_COUNT);
    }

    /**
     * Procude a StatsdDataInterface Object
     *
     * @param string $key The key of the metric
     * @param int|1 $value The amount to increment/decrement each metric by.
     * @param string|c $metric The metric type ("c" for count, "ms" for timing, "g" for gauge, "s" for set)
     * @return StatsdDataInterface
     **/
    public function produceStatsdData($key, $value = 1, $metric = StatsdDataInterface::STATSD_METRIC_COUNT)
    {
        $statsdData = $this->produceStatsdDataEntity();

        if (null !== $key) {
            $statsdData->setKey($key);
        }

        if (null !== $value) {
            $statsdData->setValue($value);
        }

        if (null !== $metric) {
            $statsdData->setMetric($metric);
        }

        return $statsdData;
    }

    /**
     * standard factory method for the StatsdDataInterface object
     * @return mixed
     */
    public function produceStatsdDataEntity() {
        $statsdData = $this->getEntityClass();
        return new $statsdData();
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
     * @param \Liuggio\StatsdClient\Entity\StatsdDataInterface $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @return \Liuggio\StatsdClient\Entity\StatsdDataInterface
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }


}
