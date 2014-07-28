<?php

namespace Liuggio\StatsdClient\Sender;

Interface SenderInterface
{
    /**
     *
     * @return mixed
     */
    public function open();

    /**
     *
     * @param        $handle
     * @param string $string
     * @param null   $length
     *
     * @return mixed
     */
    public function write($handle, $string, $length = null);

    /**
     *
     * @param $handle
     *
     * @return mixed
     */
    public function close($handle);
}
