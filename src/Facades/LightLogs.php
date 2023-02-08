<?php

namespace Lightlogs\Beacon\Facades;

use Illuminate\Support\Facades\Facade;

class LightLogs extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'collector';
    }
}
