## statsd-php-client


[![Build Status](https://secure.travis-ci.org/liuggio/statsd-php-client.png)](http://travis-ci.org/liuggio/statsd-php-client)



`statsd-php-client` is an Open Source, and **Object Oriented** Client for **etsy/statsd** written in php

- `StatsdDataFactory` creates the `Liuggio\StatsdClient\Entity\StatsdDataInterface` Objects

- `Sender` just sends data over the network

- `StatsdClient` sends the created objects via the `Sender` to the server


## Example

see the simple test file [tests/Liuggio/StatsdClient/ReadmeTest.php](https://github.com/liuggio/statsd-php-client/blob/master/tests/Liuggio/StatsdClient/ReadmeTest.php)

If you want to see the echo of the messages sent uncomment the line [#64](https://github.com/liuggio/statsd-php-client/blob/master/tests/Liuggio/StatsdClient/ReadmeTest.php#L62)


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

