<?php

namespace Liuggio\StatsdClient\Handler;

interface HandlerInterface
{
    public function send();

    public function close();

}
