<?php

namespace Liuggio\StatsdClient\Service;

Interface SenderInterface
{
    /**
     * @abstract
     * @param $hostname
     * @param null $port
     * @param null $errno
     * @param null $errstr
     * @param null $timeout
     * @return mixed
     */
    function open($hostname, $port = null, &$errno = null, &$errstr = null, $timeout = null);

    /**
     * @abstract
     * @param $handle
     * @param $string
     * @param null $length
     * @return mixed
     */
    function write($handle, $string, $length = null);

    /**
     * @abstract
     * @param $handle
     * @return mixed
     */
    function close($handle);
}
