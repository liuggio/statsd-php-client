<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Liuggio\StatsdClient\Factory\StatsdDataFactory;
use Liuggio\StatsdClient\Sender\EchoSender;
//use Liuggio\StatsdClient\Sender\SocketSender;
use Liuggio\StatsdClient\StatsdClient;
use Liuggio\StatsdClient\PacketReducer;

use Liuggio\StatsdClient\Handler\BufferHandler;
use Liuggio\StatsdClient\Handler\SendHandler;

// new sender dumping to the standard output.
$sender = new EchoSender();
//$sender = SocketSender('localhost', 8126) // new sender using socket.

$client = new StatsdClient($sender);
// add decorator to reduce packets.
$client = new PacketReducer($client);
$factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');

echo "Sending using only the client:".PHP_EOL;
$data[] = "increment:1|c";
$data[] = "set:value|s";
$data[] = "gauge:value|g";
$data[] = "timing:10|ms";
$data[] = "decrement:-1|c";
$data[] = "key:1|c";
$client->send($data);
echo "----------------".PHP_EOL;

echo "Using the Factory and sending with the Client:".PHP_EOL;
$data[] = $factory->increment(1);
$client->send($data);
echo "----------------".PHP_EOL;

echo "Using BufferHandler:".PHP_EOL;
// the handler will collect the data until a `$bufferHandler->send()` or `$bufferHandler->close()`.
$bufferHandler = new BufferHandler($client, $factory);
$bufferHandler->timing('usageTime', 100);
$bufferHandler->increment('visitor');
$bufferHandler->decrement('click');
$bufferHandler->gauge('gaugor', 333);
$bufferHandler->set('uniques', 765);
$bufferHandler->send();
echo "----------------".PHP_EOL;

echo "Using SendHandler:".PHP_EOL;
// the handler will send immediately the data.
$sendHandler = new SendHandler($client, $factory);
$sendHandler->timing('usageTime', 100);
$sendHandler->increment('visitor');
$sendHandler->decrement('click');
$sendHandler->gauge('gaugor', 333);
$sendHandler->set('uniques', 765);
// $sendHandler->send(); this function doesn't do anything.
echo "----------------".PHP_EOL;