<?php

namespace App\Traits;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait Hashable
{
    /**
     * Hash password
     *
     * @param string $string
     * @return void
     */
    protected function hash($string)
    {
        return hash('sha256', $string . config('app.key'));
    }
}
