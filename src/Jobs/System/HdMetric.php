<?php

namespace Lightlogs\Beacon\Jobs\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Lightlogs\Beacon\Collector;
use Lightlogs\Beacon\Generator;
use Lightlogs\Beacon\ExampleMetric\GenericGauge;
use Lightlogs\Beacon\ExampleMetric\GenericMultiMetric; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class HdMetric implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $hdd_free = round(disk_free_space("/"), 2);
        $hdd_total = round(disk_total_space("/"), 2);

        $hdd_used = $hdd_total - $hdd_free;
        $hdd_percent = round(sprintf('%.2f',($hdd_used / $hdd_total) * 100), 2);

        $metric = new GenericMultiMetric();
        $metric->name = 'system.hd';
        $metric->metric1 = $hdd_total; 
        $metric->metric2 = $hdd_free; 
        $metric->metric3 = $hdd_used; 
        $metric->metric4 = $hdd_percent; 

        $collector = new Collector();
        $collector->create($metric)
        ->batch();
    }
}

