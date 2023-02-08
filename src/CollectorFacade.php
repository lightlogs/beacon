<?php

namespace Lightlogs\Beacon;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Turbo124\Beacon\Skeleton\SkeletonClass
 */
class CollectorFacade extends Facade
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
