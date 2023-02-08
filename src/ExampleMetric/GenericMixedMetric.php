<?php

namespace Lightlogs\Beacon\ExampleMetric;

class GenericMixedMetric
{

    /**
     * The type of Sample
     *
     *    A mixed metric allows for multiple
     *    datapoints per time interval
     *
     *  With this data type we can use a
     *  combination of datatype to represent
     *  mixed data
     *
     *  For example we may want to count the number of
     *  times a certain IP address is hitting the server
     *  which would require a Integer and String data type
     *
     *  This metric allows 1 int, 3 doubles 
     *  and 6 strings for this metric type
     * 
     *     - mixed_metric
     *     
     * @var string
     */
    public $type = 'mixed_metric';

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
     * @var int
     */
    public $int_metric1 = 0;

    /**
     * The metric value
     * set to 0
     * 
     * @var double
     */
    public $double_metric2 = 0;

    /**
     * The metric value
     * set to 0
     * 
     * @var double
     */
    public $double_metric3 = 0;

    /**
     * The metric value
     * set to 0
     * 
     * @var double
     */
    public $double_metric4 = 0;

    /**
     * The metric value
     * set to ''
     *
     * Datatype in the database is registered as VARCHAR so note the DB limits on this column type
     * 
     * @var string
     */
    public $string_metric5 = '';

    /**
     * The metric value
     * set to ''
     *
     * Datatype in the database is registered as VARCHAR so note the DB limits on this column type
     * 
     * @var string
     */
    public $string_metric6 = '';
    
    /**
     * The metric value
     * set to ''
     *
     * Datatype in the database is registered as VARCHAR so note the DB limits on this column type
     * 
     * @var string
     */
    public $string_metric7 = '';
    
    /**
     * The metric value
     * set to ''
     *
     * Datatype in the database is registered as VARCHAR so note the DB limits on this column type
     * 
     * @var string
     */
    public $string_metric8 = '';
    
    /**
     * The metric value
     * set to ''
     *
     * Datatype in the database is registered as VARCHAR so note the DB limits on this column type
     * 
     * @var string
     */
    public $string_metric9 = '';
    
    /**
     * The metric value
     * set to ''
     *
     * Datatype in the database is registered as VARCHAR so note the DB limits on this column type
     * 
     * @var string
     */
    public $string_metric10 = '';


}