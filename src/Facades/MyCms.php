<?php

namespace Bambamboole\MyCms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bambamboole\MyCms\MyCms
 */
class MyCms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Bambamboole\MyCms\MyCms::class;
    }
}
