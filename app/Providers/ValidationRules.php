<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationRules extends ServiceProvider
{
    /**
     * Class RouteServiceProvider
     *
     * @package App\Providers
     */
    public function boot()
    {
        Validator::extend('base64image', function ($attribute, $value, $parameters, $validator) {
            foreach ($value as $item) {
                $explode = explode(',', $item);
                $allow = ['png', 'jpg', 'jpeg', 'gif'];
                $format = str_replace(
                    [
                        'data:image/',
                        ';',
                        'base64',
                    ],
                    [
                        '', '', '',
                    ],
                    $explode[0]
                );
                // check file format
                if (!in_array($format, $allow)) {
                    return false;
                }
                // check base64 format
                if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
