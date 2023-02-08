<?php

namespace Lightlogs\Beacon\Commands;

use App;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Lightlogs\Beacon\Jobs\BatchMetrics;


class ForceSend extends Command
{
    /**
     * @var string
     */
    protected $name = 'beacon:force-send';

    /**
     * @var string
     */
    protected $description = 'Forces the beacon queue to send data to the endpoint.';

    protected $log = '';

    public function handle()
    {
        $this->logMessage('Sending Data');

        $metric_types = ['counter', 'gauge', 'multi_metric', 'mixed_metric'];

        foreach($metric_types as $type)
        {
            $metrics = Cache::get(config('beacon.cache_key') . '_' . $type);

            if(is_array($metrics))
                $this->logMessage("I have " . count($metrics) . "pending to be sent");

        }

         (new BatchMetrics())->handle();

        $this->logMessage(date('Y-m-d h:i:s') . ' Sent Data!!');

        foreach($metric_types as $type)
        {
            $metrics = Cache::get(config('beacon.cache_key') . '_' . $type);

            if(is_array($metrics))
                $this->logMessage("I have " . count($metrics) . "pending to be sent");
        }


        
    }

    private function logMessage($str)
    {
        $str = date('Y-m-d h:i:s') . ' ' . $str;
        $this->info($str);
        $this->log .= $str . "\n";
    }
}
