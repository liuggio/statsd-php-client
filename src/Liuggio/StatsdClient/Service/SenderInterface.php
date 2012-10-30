<?php

namespace Liuggio\StatsdClient\Service;

Interface SenderInterface
{
    function open($hostname, $port = null, &$errno = null, &$errstr = null, $timeout = null);
    function write($handle, $string, $length = null);
    function close($handle);
}
