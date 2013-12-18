<?php

namespace Liuggio\StatsdClient\Handler;

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use Liuggio\StatsdClient\StatsdClientInterface;

/**
 * Class SendHandler
 * This Handler send over the client the packet.
 */
class SendHandler implements StatsdDataFactoryInterface, HandlerInterface
{
    private $factory;
    private $client;

    /**
     * @param StatsdClientInterface      $client
     * @param StatsdDataFactoryInterface $factory
     */
    public function __construct(StatsdClientInterface $client, StatsdDataFactoryInterface $factory)
    {
        $this->client = $client;
        $this->factory = $factory;
    }

    /**
     * {@inheritDoc}
     */
    public function send()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function timing($key, $time)
    {
        $this->client->send($this->factory->timing($key, $time));
    }

    /**
     * {@inheritDoc}
     */
    public function gauge($key, $value)
    {
        $this->client->send($this->factory->gauge($key, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->client->send($this->factory->set($key, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function increment($key)
    {
        $this->client->send($this->factory->increment($key));
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($key)
    {
        $this->client->send($this->factory->decrement($key));
    }
}
