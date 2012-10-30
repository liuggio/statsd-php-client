<?php

namespace Liuggio\StatsdClient\Service;

Class Sender implements SenderInterface
{
    /**
     * @static
     * @param $host
     * @param $port
     * @param null $errno
     * @param null $errstr
     * @param null $timeout
     * @return resource
     */
    public function open($hostname, $port = null, &$errno = null, &$errstr = null, $timeout = null) {
        $fp = fsockopen($hostname, $port, $errno, $errstr, $timeout);
        return $fp;
    }

    /**
     * @param $handle
     * @param $message
     * @return int
     */
    function write($handle, $message, $length = null){
        return fwrite($handle, $message, $length);
    }

    /**
     * @param $handle
     */
    function close($handle){
        fclose($handle);
    }
}
