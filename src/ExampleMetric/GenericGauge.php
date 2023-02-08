<?php

namespace Lightlogs\Beacon\ExampleMetric;

class GenericGauge 
{

	/**
	 * The type of Sample
	 *
	 *	A gauge can be used to measure a given value 
	 *	per time interval
	 * 
	 * 	- gauge
	 * 	
	 * @var string
	 */
	public $type = 'gauge';

	/**
	 * The name of the counter
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
	 * @var double
	 */
	public $metric = 0;

}