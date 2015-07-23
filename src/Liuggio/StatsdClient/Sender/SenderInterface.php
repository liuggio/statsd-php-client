<?php

namespace Liuggio\StatsdClient\Sender;

Interface SenderInterface
{
    /**
     * @return mixed
     */
    function open();

    /**
     * @param        $handle
     * @param string $string
     * @param null   $length
     *
     * @return mixed
     */
    function write($handle, $string, $length = null);

    /**
     * @param $handle
     *
     * @return mixed
     */
    function close($handle);
}
