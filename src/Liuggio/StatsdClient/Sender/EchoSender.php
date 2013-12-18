<?php

namespace Liuggio\StatsdClient\Sender;

Class EchoSender implements SenderInterface
{
    /**
     * {@inheritDoc}
     */
    public function open()
    {
        echo "[open]".PHP_EOL;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function write($handle, $message, $length = null)
    {
        echo "[$message]".PHP_EOL;

        return strlen($message);
    }

    /**
     * {@inheritDoc}
     */
    public function close($handle)
    {
        echo "[closed]".PHP_EOL;
    }
}
