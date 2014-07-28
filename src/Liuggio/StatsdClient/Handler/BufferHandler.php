<?php

namespace Liuggio\StatsdClient\Handler;

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use Liuggio\StatsdClient\StatsdClientInterface;

/**
 * This Handler put all the data in a buffer until a send or close is called.
 */
class BufferHandler implements StatsdDataFactoryInterface, HandlerInterface
{
    private $factory;
    private $client;
    private $buffer;

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
        return $this->client->send($this->popBuffer());
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        return $this->send();
    }

    /**
     * {@inheritDoc}
     */
    public function timing($key, $time)
    {
        $this->addToBuffer($this->factory->timing($key, $time));
    }

    /**
     * {@inheritDoc}
     */
    public function gauge($key, $value)
    {
        $this->addToBuffer($this->factory->gauge($key, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->addToBuffer($this->factory->set($key, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function increment($key)
    {
        $this->addToBuffer($this->factory->increment($key));
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($key)
    {
        $this->addToBuffer($this->factory->decrement($key));
    }

    public function __destruct()
    {
        try {
            $this->close();
        } catch (\Exception $e) {
            // do nothing
        }
    }

    private function addToBuffer($data)
    {
        $this->buffer[] = $data;
    }

    private function popBuffer()
    {
        $buffer = $this->buffer;
        $this->buffer = array();

        return $buffer;
    }
}
