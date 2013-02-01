## statsd-php-client


[![Build Status](https://secure.travis-ci.org/liuggio/statsd-php-client.png)](http://travis-ci.org/liuggio/statsd-php-client)



`statsd-php-client` is an Open Source, and **Object Oriented** Client for **etsy/statsd** written in php

- `StatsdDataFactory` creates the `Liuggio\StatsdClient\Entity\StatsdDataInterface` Objects

- `Sender` just sends data over the network

- `StatsdClient` sends the created objects via the `Sender` to the server

## Why use this library instead the [statsd/php-example](https://github.com/etsy/statsd/blob/master/examples/php-example.php)?

- You are wise.

- This library is totally tested.

- This library optimizes the messages to send, compressing multiple messages in individual UDP packets.

- This library pays attention to the maximum length of the UDP.

- This library is made by Objects not array, but it also accepts array.



## Example

```php

        $sender = new SocketSender();

        // StatsdClient(SenderInterface $sender, $host = 'udp://localhost', $port = 8126, $reducePacket = true, $fail_silently = true)
        $client = new StatsdClient($sender);
        $factory = new StatsdDataFactory('\\Liuggio\\StatsdClient\\Entity\\StatsdData');

        // create the data with the factory
        $data[] = $factory->timing('usageTime', 100);
        $data[] = $factory->increment('visitor');
        $data[] = $factory->decrement('click');
        $data[] = $factory->gauge('gaugor', 333);
        $data[] = $factory->set('uniques', 765);

        // send the data as array or directly as object
        $client->send($data);
```

## Short Theory

### Easily Install StatSD and Graphite

In order to try this application monitor you have to install etsy/statsd and Graphite

see this blog post to install it with vagrant [Easy install statsd graphite](http://welcometothebundle.com/easily-install-statsd-and-graphite-with-vagrant/).


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
