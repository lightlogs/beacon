# Beacon - A laravel application metric collector package.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/turbo124/collector.svg?style=flat-square)](https://packagist.org/packages/turbo124/collector)
[![Total Downloads](https://img.shields.io/packagist/dt/turbo124/collector.svg?style=flat-square)](https://packagist.org/packages/turbo124/collector)

This collector implements a native Laravel solution for collecting application statistics. Currently to enable this kind of functionality you would need to install Node and StatsD to your host and then begin piping your data. You'd then need to install a third party application to ingest the data and then display.

This client can be paired with the Lightlogs collector package which can be installed on any Laravel installation to ingest the mertrics.

## Installation

You can install the package via composer:

```bash
composer require lightlogs/beacon
```

## Usage
The default method to send metrics is to create a static property class (see /src/ExampleMetric/GenericCounter) and build a collect like this

``` php

LightLogs::create(new GenericCounter())
        ->increment()
        ->batch();
```

This will batch the metric requests and an underlying Scheduler job will process all metric every 5 minutes (please note you will need to have the Laravel Scheduler running for these jobs to be dispatched).

Batching uses Guzzle async requests under the hood to improve efficiency and minimize the time the collector is working.

Whilst not advised to do this in production due to the latency overhead, if your metric needs to be fired immediately you can do this syncronously using the following.

``` php

LightLogs::create(new GenericCounter())
        ->increment()
        ->send();
```

A better way to handle jobs that need to be fired immediately without blocking would be to use the ->queue() method which will dispatch a Job onto the applications queue.

``` php

LightLogs::create(new GenericCounter())
        ->increment()
        ->queue();
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

