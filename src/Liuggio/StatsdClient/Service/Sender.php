<?php

namespace Liuggio\StatsdClient\Service;

Class Sender implements SenderInterface
{
    /**
     * @inherit
     */
    public function open($hostname, $port = null, &$errno = null, &$errstr = null, $timeout = null) {
        $fp = fsockopen($hostname, $port, $errno, $errstr, $timeout);
        return $fp;
    }

    /**
     * @inherit
     */
    function write($handle, $message, $length = null){
        return fwrite($handle, $message, $length);
    }

    /**
     * @inherit
     */
    function close($handle){
        fclose($handle);
    }
}
