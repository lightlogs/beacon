<?php

namespace Lightlogs\Beacon\Commands;

use App;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Lightlogs\Beacon\Jobs\BatchMetrics;


class PurgeAnalytics extends Command
{
    /**
     * @var string
     */
    protected $name = 'beacon:purge';

    /**
     * @var string
     */
    protected $description = 'Purging any analytics in the cache';

    protected $log = '';

    public function handle()
    {
        $this->logMessage('Purging Data');
            
            $metric_types = ['counter', 'gauge', 'multi_metric', 'mixed_metric'];

        foreach($metric_types as $type)
            {

            $this->logMessage("purging {$type}");

            Cache::forget(config('beacon.cache_key') . '_' . $type);
        }

        $this->logMessage('Finished Purging Data');

        
    }

    private function logMessage($str)
    {
        $str = date('Y-m-d h:i:s') . ' ' . $str;
        $this->info($str);
        $this->log .= $str . " \n";
    }
}
