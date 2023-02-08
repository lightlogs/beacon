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

class NetworkMetric implements ShouldQueue
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
        $vnstat = popen("vnstat --json", 'r');

        if (is_resource($vnstat)) { 
            $stream = '';
        }


        while (!feof($vnstat)) {
            $stream .= fgets($vnstat);
        }

        // Close the handle
        pclose($vnstat);
  
        $x = json_decode($stream);

        foreach($x->interfaces as $interface)
        {
  
            $name = $interface->name;
            $network_metric = end($interface->traffic->fiveminute);

            $metric = new GenericMultiMetric();
            $metric->name = 'network.activity.'.$name;
            $metric->metric1 = $network_metric->rx; 
            $metric->metric2 = $network_metric->tx; 
            // $metric->metric4 = $stat['mem_percent']; 

            $collector = new Collector();
            $collector->create($metric)
                ->batch();


        }

    }
}



/*
function formatBytes($bytes)
{
    $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB'];
    $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow   = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes) . ' ' . $units[$pow];
}

function formatBitrate($bytes, $seconds)
{
    $units = ['bit', 'kbit', 'mbit', 'gbit', 'tbit'];
    $bits  = ($bytes * 8) / $seconds;
    $pow   = floor(($bits ? log($bits) : 0) / log(1024));
    $pow   = min($pow, count($units) - 1);
    $bits  /= (1 << (10 * $pow));

    return round($bits, 2) . ' ' . $units[$pow] . '/s';
}

function formatRatio($bytesReceived, $bytesSent)
{
    $total = $bytesReceived + $bytesSent;
    $percentageReceived = ($bytesReceived / $total * 100);

    return sprintf(
        '<div class="ratio"><div style="width: %f%%;"></div></div>',
        $percentageReceived
    );
}

*/