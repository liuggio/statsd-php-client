<?php

namespace Liuggio\StatsdClient\Factory;

Interface StatsdDataFactoryInterface
{
    /**
     * This public function creates a 'timing' StatsdData.
     *
     * @abstract
     *
     * @param string|array $key  The metric(s) to set.
     * @param float        $time The elapsed time (ms) to log
     **/
    public function timing($key, $time);

    /**
     * This public function creates a 'gauge' StatsdData.
     *
     * @abstract
     *
     * @param string|array $key   The metric(s) to set.
     * @param float        $value The value for the stats.
     **/
    public function gauge($key, $value);

    /**
     * This public function creates a 'set' StatsdData object
     * A "Set" is a count of unique events.
     * This data type acts like a counter, but supports counting
     * of unique occurrences of values between flushes. The backend
     * receives the number of unique events that happened since
     * the last flush.
     *
     * The reference use case involved tracking the number of active
     * and logged in users by sending the current userId of a user
     * with each request with a key of "uniques" (or similar).
     *
     * @abstract
     *
     * @param string|array $key   The metric(s) to set.
     * @param float        $value The value for the stats.
     *
     * @return array
     **/
    public function set($key, $value);

    /**
     * This public function creates a 'increment' StatsdData object.
     *
     * @abstract
     *
     * @param string|array $key        The metric(s) to increment.
     * @param float|1      $sampleRate The rate (0-1) for sampling.
     *
     * @return array
     **/
    public function increment($key);

    /**
     * This public function creates a 'decrement' StatsdData object.
     *
     * @abstract
     *
     * @param string|array $key        The metric(s) to decrement.
     * @param float|1      $sampleRate The rate (0-1) for sampling.
     *
     * @return mixed
     **/
    public function decrement($key);

}
