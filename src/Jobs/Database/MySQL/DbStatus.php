<?php

namespace Lightlogs\Beacon\Jobs\Database\MySQL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Lightlogs\Beacon\Collector;
use Lightlogs\Beacon\ExampleMetric\GenericGauge;
use Lightlogs\Beacon\ExampleMetric\GenericMixedMetric;
use Lightlogs\Beacon\ExampleMetric\GenericMultiMetric;
use Lightlogs\Beacon\Generator;
use Lightlogs\Beacon\Jobs\Database\Traits\StatusVariables;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class DbStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, StatusVariables;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $db_connection;

    private $name;
    
    private $force_send;

    public function __construct(string $db_connection, string $name, bool $force_send = false)
    {
        $this->db_connection = $db_connection;

        $this->name = $name;

        $this->force_send = $force_send;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        config(['database.default' => $this->db_connection]);

        $db_status = $this->checkDbConnection();

        $metric = new GenericGauge();
        $metric->name = $this->name;
        $metric->metric = (int)$db_status;

        $collector = (new Collector());

        if($this->force_send || !$db_status){ //if there is no DB connection, then we MUST fire immediately!!
            (new Collector())->create($metric)->send();
        }
        else{
            (new Collector())->create($metric)->batch();
        }

        $variables = $this->getSlaveVariables();

        if($variables)
        {

            $metric = new GenericMixedMetric();
            $metric->name = 'database.slave_status.'.$this->db_connection;
            $metric->string_metric5 = $variables->Master_Host; 
            $metric->string_metric6 = $variables->Slave_IO_Running; 
            $metric->string_metric7 = $variables->Slave_SQL_Running; 
            $metric->string_metric8 = substr($variables->Last_Error, 0, 150); 

            $collector = new Collector();
            $collector->create($metric)
            ->batch();

        }

        $status_variables = $this->getVariables();

        if($status_variables)
        {
            $metric = new GenericMultiMetric();
            $metric->name = 'database.performance.'.$this->db_connection;
            $metric->metric1 = $status_variables->Innodb_data_read;
            $metric->metric2 = $status_variables->Innodb_data_writes;
            $metric->metric3 = $status_variables->Max_used_connections;
            $metric->metric4 = $status_variables->Table_locks_immediate;
            $metric->metric5 = $status_variables->Table_locks_waited;

            
            $collector = new Collector();
            $collector->create($metric)
            ->batch();
        }

    }


}