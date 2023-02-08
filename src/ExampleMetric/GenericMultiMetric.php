<?php

namespace Lightlogs\Beacon\ExampleMetric;

class GenericMultiMetric
{
    /**
     * The type of Sample
     *
     *    A multi metric allows for multiple
     *    datapoints per time interval
     *
     *     - multi_metric
     *
     * @var string
     */
    public $type = 'multi_metric';

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
     * The metric value
     * set to 0
     *
     * @var double
     */
    public $metric1 = 0;

    /**
     * The metric value
     * set to 0
     *
     * @var double
     */
    public $metric2 = 0;

    /**
     * The metric value
     * set to 0
     *
     * @var double
     */
    public $metric3 = 0;

    /**
     * The metric value
     * set to 0
     *
     * @var double
     */
    public $metric4 = 0;

    /**
     * The metric value
     * set to 0
     *
     * @var double
     */
    public $metric5 = 0;
}
