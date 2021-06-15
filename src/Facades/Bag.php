<?php

namespace Laraware\Bag\Facades;

use Illuminate\Support\Facades\Facade;

class Bag extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bag';
    }
}
