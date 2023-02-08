<?php

namespace Lightlogs\Beacon\Jobs\Database\MySQL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Lightlogs\Beacon\Collector;
use Lightlogs\Beacon\Generator;
use Lightlogs\Beacon\ExampleMetric\GenericMixedMetric;
use Lightlogs\Beacon\Jobs\Database\Traits\StatusVariables; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class SlaveStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, StatusVariables;

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
        $slave_connection = config('beacon.database.mysql.slave');

        config(['database.default' => $slave_connection]);

        $variables = $this->getSlaveVariables();

        if(!$variables) {
            return;
        }

        $metric = new GenericMixedMetric();
        $metric->name = 'database.slave_status';
        $metric->string_metric5 = $variables->Master_Host; 
        $metric->string_metric6 = $variables->Slave_IO_Running; 
        $metric->string_metric7 = $variables->Slave_SQL_Running; 
        $metric->string_metric8 = $variables->Replicate_Do_DB; 
        $metric->string_metric6 = $variables->Last_Error; 

        $collector = new Collector();
        $collector->create($metric)
            ->batch();

    }


}
