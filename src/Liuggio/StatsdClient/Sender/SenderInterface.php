<?php

namespace Liuggio\StatsdClient\Sender;

Interface SenderInterface
{
    /**
     * @abstract
     * @param $hostname
     * @param null $port
     * @param null $protocol
     * @return mixed
     */
    function open($hostname, $port = null, $protocol = null);

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
