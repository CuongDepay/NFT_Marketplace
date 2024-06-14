<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        JsonResource::withoutWrapping();
        Validator::extend('phone_vietnam', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^0[0-9]{9}$/', $value);
        });
    }
}
