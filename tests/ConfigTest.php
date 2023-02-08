<?php

namespace Lightlogs\Beacon\Tests;

use PHPUnit\Framework\TestCase;
use Lightlogs\Beacon\Collector;
use Lightlogs\Beacon\CollectorServiceProvider;
use Lightlogs\Beacon\Beacon\Generator;

class ConfigTest extends TestCase
{
    /** @test */
	public function testValidInstanceType()
	{
		$collector = new Collector;
		$this->assertTrue($collector instanceof Collector);
	}
}
