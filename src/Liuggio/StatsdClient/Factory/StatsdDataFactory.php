<?php

namespace Liuggio\StatsdClient\Factory;

use Liuggio\StatsdClient\Entity\StatsdData;

class StatsdDataFactory implements StatsdDataFactoryInterface
{
    /** @var StatsdData */
    private $entityClass;
    /** @var boolean */
    private $failSilently;

    public function __construct($entity_class = '\Liuggio\StatsdClient\Entity\StatsdData')
    {
        $this->setEntityClass($entity_class);
    }

    /**
     * {@inheritDoc}
     */
    public function timing($key, $time)
    {
        return $this->produceStatsdData($key, $time, StatsdData::STATSD_METRIC_TIMING);
    }

    /**
     * {@inheritDoc}
     */
    public function gauge($key, $value)
    {
        return $this->produceStatsdData($key, $value, StatsdData::STATSD_METRIC_GAUGE);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        return $this->produceStatsdData($key, $value, StatsdData::STATSD_METRIC_SET);
    }

    /**
     * {@inheritDoc}
     */
    public function increment($key)
    {
        return $this->produceStatsdData($key, 1, StatsdData::STATSD_METRIC_COUNT);
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($key)
    {
        return $this->produceStatsdData($key, -1, StatsdData::STATSD_METRIC_COUNT);
    }

    /**
     * {@inheritDoc}
     */
    public function updateCount($key, $delta)
    {
        return $this->produceStatsdData($key, $delta, StatsdData::STATSD_METRIC_COUNT);
    }

    /**
     * {@inheritDoc}
     */
    public function produceStatsdData($key, $value = 1, $metric = StatsdData::STATSD_METRIC_COUNT)
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
     * @return StatsdData
     * @deprecated
     */
    public function produceStatsdDataEntity()
    {
        $statsdData = $this->getEntityClass();

        return new $statsdData();
    }

    /**
     * @deprecated
     */
    public function setFailSilently($failSilently)
    {
        $this->failSilently = $failSilently;
    }

    /**
     * @deprecated
     */
    public function getFailSilently()
    {
        return $this->failSilently;
    }

    /**
     * @deprecated
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @deprecated
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }
}
