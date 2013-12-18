<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Liuggio\StatsdClient\Factory\StatsdDataFactory;
use Liuggio\StatsdClient\Sender\EchoSender;
//use Liuggio\StatsdClient\Sender\SocketSender;
use Liuggio\StatsdClient\StatsdClient;
use Liuggio\StatsdClient\PacketReducer;
use Monolog\Logger;
use Liuggio\StatsdClient\Monolog\Handler\StatsDHandler;

// new sender dumping to the standard output.
$sender = new EchoSender();
//$sender = SocketSender('localhost', 8126) // new sender using socket.
$client = new StatsdClient($sender);
// add decorator to reduce packets.
$client = new PacketReducer($client);
$factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');

echo "Using monolog to send data to statsd:".PHP_EOL;
// Create the logger
$logger = new Logger('my_logger');
// Now add StatsD monolog-handler
$logger->pushHandler(new StatsDHandler($client, $factory, 'prefix', Logger::DEBUG));
// You can now use your logger
$logger->addInfo('My logger is now ready');