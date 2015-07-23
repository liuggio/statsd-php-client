<?php

namespace Liuggio\StatsdClient\Entity;

/**
 * @deprecated Use The Entity StatsdData
 */
interface StatsdDataInterface
{
    /** @deprecated */
    CONST STATSD_METRIC_TIMING = 'ms';
    /** @deprecated */
    CONST STATSD_METRIC_GAUGE  = 'g';
    /** @deprecated */
    CONST STATSD_METRIC_SET    = 's';
    /** @deprecated */
    CONST STATSD_METRIC_COUNT  = 'c';

    /**
     * @abstract
     * @return string
     */
    function getKey();

    /**
     * @abstract
     * @return mixed
     */
    function getValue();

    /**
     * @abstract
     * @return string
     */
    function getMetric();

    /**
     * @abstract
     * @return string
     */
    function getMessage();

    /**
     * @abstract
     * @return float
     */
    function getSampleRate();

    /**
     * @abstract
     * @return string
     */
    function __toString();
}
