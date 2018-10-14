<?php

namespace Sausin\LaravelOvh;

use Illuminate\Support\Facades\Facade;


class OVHFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ovh';
    }
}
