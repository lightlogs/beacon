# Beacon - A laravel application metric collector package.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/turbo124/collector.svg?style=flat-square)](https://packagist.org/packages/turbo124/collector)
[![Total Downloads](https://img.shields.io/packagist/dt/turbo124/collector.svg?style=flat-square)](https://packagist.org/packages/turbo124/collector)

![logo](https://user-images.githubusercontent.com/5827962/217441851-3b64f31e-47e8-4661-9e03-23334217dacb.png)


This collector implements a native Laravel solution for collecting and sending application statistics. This client is designed to be paired with the Lightlogs collector package which can be installed on any Laravel 9+ installation to ingest the metrics.

Why the need for a collector like this? Collecting application statistics can be very useful to understand how your application is working, detect any hot spots before they cause troubles and also understand how users are interacting (or abusing) your system. For instance you could create a metric to monitor HTTP requests received by your application and use this to manage your resources / provisioning of your infrastructure. This data can then be presented in a graphical display system such as Grafana which enables very detailed time series charts, see the example below:

![grafana](https://user-images.githubusercontent.com/5827962/217438219-6f389ce7-f41c-49b7-8e0c-edbe85d94c60.png)

It is highly recommended to send your metrics to a different endpoint than the one you are monitoring. In the case of an outage, you will be able to see what your application was doing just prior to the event. This could provide useful information that can guide you to the source of any issues.

In our use case we pair [Grafana](https://grafana.com/) with [Timescale](https://www.timescale.com/) to store and present our data. 

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

### Counter

The default method to send metrics is to create a plain old PHP class (example for each type can be found in src/ExampleMetric), here is an example of a counter metric. 

```php
namespace Lightlogs\Beacon\ExampleMetric;

class SpecialCounter extends GenericCounter
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

### Mixed Metrics

In the next example we'll be collecting metrics on visitors to our API, we'll use the MixedMetrics collector type to capture a range of data using a custom class like this:

```php
namespace App\Beacon;

use Lightlogs\Beacon\ExampleMetric\GenericMixedMetric;

class DbQuery extends GenericMixedMetric
{
    /**
     * The type of Sample.
     *
     * @var string
     */
    public $type = 'mixed_metric';

    /**
     * The name of the metric.
     * @var string
     */
    public $name = 'db.queries';

    /**
     * The datetime of the metric measurement.
     *
     * date("Y-m-d H:i:s")
     *
     * @var DateTime
     */
    public $datetime;

    /**  
     * @var string
     */
    public $string_metric5 = 'method';

    /**  
     * @var string
     */
    public $string_metric6 = 'url';

    /**  
     * @var string
     */
    public $string_metric7 = 'ip_address';

    /**
     * @var int
     */
    public $int_metric1 = 1;

    /**
     * @var int
     */
    public $double_metric2 = 1;

    public function __construct($string_metric5, $string_metric6, $int_metric1, $double_metric2, $string_metric7)
    {
        $this->string_metric5 = $string_metric5;
        $this->string_metric6 = $string_metric6;
        $this->int_metric1 = $int_metric1;
        $this->double_metric2 = $double_metric2;
        $this->string_metric7 = $string_metric7;
    }
}

```

Within your application where you will be capturing this data, you would construct your collector like this:

```php
LightLogs::create(new DbQuery($request_method, 
                              $url, 
                              $count, 
                              $request_duration, 
                              $ip_address))
                            ->batch();
```

### Command line options

From the command line you can force send statistics:

```bash
php artisan beacon:force-send
```

Or purge statistics from the cache:

```bash
php artisan beacon:purge
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

