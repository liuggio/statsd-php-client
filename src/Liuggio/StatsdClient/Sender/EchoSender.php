<?php

namespace Liuggio\StatsdClient\Sender;


Class EchoSender implements SenderInterface
{
    /**
     * {@inherit}
     */
    public function open($hostname, $port = null, $protocol = null) {
        echo "[open]";
        return true;
    }

    /**
     * {@inherit}
     */
    function write($handle, $message, $length = null){
        echo "[$message]";
        return strlen($message);
    }

    /**
     * {@inherit}
     */
    function close($handle){
        echo "[closed]";
    }
}
