<?php

namespace Liuggio\StatsdClient\Sender;


Class EchoSender implements SenderInterface
{
    /**
     * {@inheritDoc}
     */
    public function open($hostname, $port = null, $protocol = null) {
        echo "[open]";
        return true;
    }

    /**
     * {@inheritDoc}
     */
    function write($handle, $message, $length = null){
        echo "[$message]";
        return strlen($message);
    }

    /**
     * {@inheritDoc}
     */
    function close($handle){
        echo "[closed]";
    }
}
