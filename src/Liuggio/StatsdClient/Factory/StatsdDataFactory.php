<?php

namespace Liuggio\StatsdClient\Factory;

use Liuggio\StatsdClient\Entity\StatsdDataInterface;


class StatsdDataFactory implements StatsdDataFactoryInterface
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
     * {@inherit}
     **/
    public function timing($key, $time)
    {
        return $this->produceStatsdData($key, $time, StatsdDataInterface::STATSD_METRIC_TIMING);
    }

    /**
     * {@inherit}
     **/
    public function gauge($key, $value)
    {
        return $this->produceStatsdData($key, $value, StatsdDataInterface::STATSD_METRIC_GAUGE);
    }

    /**
     * {@inherit}
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
     * {@inherit}
     **/
    public function decrement($key)
    {
        return $this->produceStatsdData($key, -1, StatsdDataInterface::STATSD_METRIC_COUNT);
    }

    /**
     * {@inherit}
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
     * {@inherit}
     **/
    public function produceStatsdDataEntity() {
        $statsdData = $this->getEntityClass();
        return new $statsdData();
    }

    /**
     * {@inherit}
     **/
    public function setFailSilently($failSilently)
    {
        $this->failSilently = $failSilently;
    }

    /**
     * {@inherit}
     **/
    public function getFailSilently()
    {
        return $this->failSilently;
    }

    /**
     * {@inherit}
     **/
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * {@inherit}
     **/
    public function getEntityClass()
    {
        return $this->entityClass;
    }
}
