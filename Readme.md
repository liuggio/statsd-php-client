## statsd-php-client

Be careful, see the [Upgrading section](Readme.md#upgrade) for <= v1.0.4, there's a BC, and for  1.0.*

[![Build Status](https://secure.travis-ci.org/liuggio/statsd-php-client.png)](http://travis-ci.org/liuggio/statsd-php-client) [![Latest Stable Version](https://poser.pugx.org/liuggio/statsd-php-client/v/stable.png)](https://packagist.org/packages/liuggio/statsd-php-client) [![Total Downloads](https://poser.pugx.org/liuggio/statsd-php-client/downloads.png)](https://packagist.org/packages/liuggio/statsd-php-client)

`statsd-php-client` is an Open Source, and **Object Oriented** Client for **etsy/statsd** written in php

- `StatsdDataFactory` creates the `Liuggio\StatsdClient\Entity\StatsdDataInterface` Objects

- `Sender` just sends data over the network (there are many sender)

- `StatsdClient` sends the created objects via the `Sender` to the server

- `Handler` could be

## Why use this library instead the [statsd/php-example](https://github.com/etsy/statsd/blob/master/examples/php-example.php)?

- You are wise.

- You could also use monolog to redirect data to StatsD

- This library is tested.

- This library optimizes the messages to send, compressing multiple messages in individual UDP packets.

- This library pays attention to the maximum length of the UDP.

- This library is made by Objects not array, but it also accepts array.

- You do want to debug the packets, and using `SysLogSender` the packets will be logged in your `syslog` log (on debian-like distro: `tail -f /var/log/syslog`)

## Example

``` php
$sender = new EchoSender();                 // new sender dumping to the standard output.
//$sender = SocketSender('localhost', 8126) // new sender using socket.
$client = new StatsdClient($sender);        // the client uses the sender to send data
// add client decorator to reduce packets.
$client = new PacketReducer($client);       // the decorator reduces the packet to send
$factory = new StatsdDataFactory();         // the factory create the packet

$sendHandler = new SendHandler($client, $factory); // the Handler will create and send the data
$sendHandler->timing('usageTime', 100);     // create the timing object and send it.
// ...
$bufferHandler = new BufferHandler($client, $factory); // this handler will buffer the data
$bufferHandler->timing('usageTime', 100);   // create and buffers the data
// ...
$bufferHandler->send();                     // sends all the buffered data
```

### Standard Usage

```php
use Liuggio\StatsdClient\StatsdClient,
    Liuggio\StatsdClient\Factory\StatsdDataFactory,
    Liuggio\StatsdClient\Sender\SocketSender;
// use Liuggio\StatsdClient\PacketReducer;
// use Liuggio\StatsdClient\Sender\SysLogSender;

$sender = new SocketSender(/*'localhost', 8126, 'udp'*/);
// $sender = new SysLogSender(); // enabling this, the packet will not send over the socket

$client = new StatsdClient($sender);
// $client = new PacketReducer($client); // if you want to compose socket packets with multi metric

$factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');

// create the data with the factory
$data[] = $factory->timing('usageTime', 100);
$data[] = $factory->increment('visitor');
$data[] = $factory->decrement('click');
$data[] = $factory->gauge('gaugor', 333);
$data[] = $factory->set('uniques', 765);

// send the data as array or directly as object
$client->send($data);
```

more info at [example.php](src/example/example.php)

### Usage with Monolog

please have a look to [example with monolog.php](src/example/monolog.php)

## Short Theory

### Easily Install StatsD and Graphite

There are a lot of resources on internet on how to install etsy/statsd and Graphite,
eg. [Easy install statsd graphite](http://welcometothebundle.com/easily-install-statsd-and-graphite-with-vagrant/).

#### [StatsD](https://github.com/etsy/statsd)

StatsD is a simple daemon for easy stats aggregation

#### [Graphite](http://graphite.wikidot.com/)

Graphite is a Scalable Realtime Graphing

#### The Client send data with UDP (faster)

https://www.google.com/search?q=tcp+vs+udp

## Contribution

Active contribution and patches are very welcome.
To keep things in shape we have quite a bunch of unit tests. If you're submitting pull requests please
make sure that they are still passing and if you add functionality please
take a look at the coverage as well it should be pretty high :)

- First fork or clone the repository

```
git clone git://github.com/liuggio/statsd-php-client.git
cd statsd-php-client
```

- Install vendors:

``` bash
composer.phar install
```

- This will give you proper results:

``` bash
phpunit --coverage-html reports
```

## Upgrading

- BC from the v1.0.4 version, [see Sender and Client differences](https://github.com/liuggio/statsd-php-client/pull/5/files).

- BC from the v1.0.* version:
  * The StatsdClientInterface::MAX_UDP_SIZE_STR is deprecated.
  * The StastdClient::constructor permit only to parameters not 3, the boolean packet reducer has been removed,
    in favour of the PacketReducer Class that act as decorator.
  * The methods of StastdClient are not public anymore:
     - setFailSilently
     - getFailSilently
     - setSender
     - getSender
  * The Factory\StatsdDataFactoryInterface::produceStatsdData has been removed
  * The Factory\StatsdDataFactory::produceStatsdData has been marked as protected