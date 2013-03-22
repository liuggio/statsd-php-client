<?php

namespace Liuggio\StatsdClient\Sender;

use Liuggio\StatsdClient\Exception\InvalidArgumentException;

Class SocketSender implements SenderInterface
{
    private $port;
    private $host;
    /**
     * {@inheritDoc}
     */
    public function open($hostname, $port = null, $protocol = null)
    {
        $this->host = $hostname;
        $this->port = $port;

        switch ($protocol) {
            case 'udp':
                $protocolSOL = SOL_UDP;
                break;
            case 'tcp':
                $protocolSOL = SOL_TCP;
                break;
            default:
                throw new InvalidArgumentException('use udp or tcp as protocol');
                break;
        }

        $fp = socket_create(AF_INET, SOCK_DGRAM, $protocolSOL);
        return $fp;
    }

    /**
     * {@inheritDoc}
     */
    function write($handle, $message, $length = null)
    {

        return socket_sendto($handle, $message, strlen($message), 0, $this->host, $this->port);
    }

    /**
     * {@inheritDoc}
     */
    function close($handle)
    {
        socket_close($handle);
    }
}
