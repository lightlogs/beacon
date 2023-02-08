<?php

namespace Lightlogs\Beacon\Jobs;

use Illuminate\Http\Request;
use Lightlogs\Beacon\Generator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateMetric implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 4;

    protected $metric;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($metric)
    {
        $this->metric = $metric;
    }

    public function backoff()
    {
        return [5, 60, 240, 3600];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $generator = new Generator();
        $generator->fire($this->metric);
    }
}
