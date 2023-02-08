# Beacon - A laravel application metric collector package.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/turbo124/collector.svg?style=flat-square)](https://packagist.org/packages/turbo124/collector)
[![Total Downloads](https://img.shields.io/packagist/dt/turbo124/collector.svg?style=flat-square)](https://packagist.org/packages/turbo124/collector)

This collector implements a native Laravel solution for collecting application statistics. Currently to enable this kind of functionality you would need to install Node and StatsD to your host and then begin piping your data. You'd then need to install a third party application to ingest the data and then display.

This client is designed to be paired with the Lightlogs collector package which can be installed on any Laravel 9+ installation to ingest the metrics.

## Installation

You can install the package via composer:

```bash
composer require lightlogs/beacon
```

## Configuration

Update config/beacon.php with your BEACON_ENDPOINT and BEACON_API_KEY. By default BEACON_ENABLED is set to false, when you are ready to enable the package, you'll want to set this to TRUE.

## Overview

This package enables you to send several different metric types depending on what you would like to log, there are currently 4 types supported:

- Counter: If you wish to log pageviews or visitors, you would use a counter metric. This is a simple increment/decrement counter that is logged against time.
- Gauge: If you need to log a specific value over time, a gauge metric would be used which would allow you to store a double value over time.
- MultiMetric: If you need to log up to 5 double values over time, the multi metric could be used. ie disk / memory consumption 
- MixedMetric: If you need to log a range of data points, the mixed metric would be suitable, it allows a range of integers / doubles and string values to be captured. This could suit logging URLs visited on your site.

## Usage

The default method to send metrics is to create a plain old PHP class (example for each type can be found in src/ExampleMetric), here is an example of a counter metric


```
namespace Lightlogs\Beacon\ExampleMetric;

class GenericCounter
{
    /**
     * The type of Sample
     *
     * Monotonically incrementing counter
     *
     * @var string
     */
    public $type = 'counter';

    /**
     * The name of the counter
     *
     * @var string
     */
    public $name = '';

    /**
     * The datetime of the counter measurement
     *
     * date("Y-m-d H:i:s")
     *
     * @var DateTime
     */
    public $datetime;

    /**
     * The increment amount... should always be
     * set to 0
     *
     * @var integer
     */
    public $metric = 0;
}
```

- string $type defines the metric type, in this case 'counter'
- string $name defines the string key used to define this metric, for example if we are counting the number of user logins an example name could be 'user.login' you would later use this in your DB queries when charting your metrics
- datetime $datetime defines the datetime that this event was logged at. This is auto populated by the system, however you can override this.
- int $metric defines the increment amount of the amount. By default the system will apply an increment value of 1 or a decrement value of -1, otherwise this field can also be overwritten if needed.

Once you have created your class, whenever you need to create a counter metric, it would look like this:

``` php

LightLogs::create(new GenericCounter())
        ->increment()
        ->batch();
```

This will batch the metric requests in order to preserve system resources. An underlying Scheduler job will process all metrics every 5 minutes (please note you will need to have the Laravel Scheduler running for these jobs to be dispatched).

Batching uses Guzzle async requests under the hood to improve efficiency and minimize the time the collector is working.

Whilst not advised to do this in production due to the latency overhead, if your metric needs to be fired immediately you can do this syncronously using the following.

``` php

LightLogs::create(new GenericCounter())
        ->increment()
        ->send();
```

A better way to handle jobs that need to be fired immediately without blocking would be to use the ->batch() or ->queue() method which will dispatch a Job onto the applications queue.

``` php

LightLogs::create(new GenericCounter())
        ->increment()
        ->queue();
```

Included in this package is a range of system metric collectors these can be enabled or disabled by modifying the system_logging array in config/beacon.php 

## Usage outside of Lightlogs collector


It is possible to use this package with your own custom endpoint for consuming metrics, the URI path that is constructed follows this pattern

```php
BEACON_ENDPOINT/METRIC_TYPE/batch

REQUEST TYPE = POST
``` 

For example if you wish to send a counter metric the full URL would look like this:

```
https://endpoint.com/counter/batch
```



### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email turbo124@gmail.com instead of using the issue tracker.

## Credits

- [David Bomba](https://github.com/turbo124)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

