<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Support\Facades\Session;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Session::resolved(function ($session) {
            $session->extend('custom', function ($app) {
                $table = $app['config']['session.table'];
                $lifetime = $app['config']['session.lifetime'];
                $connection = $app['db']->connection($app['config']['session.connection']);
                return new DatabaseSessionHandler($connection, $table, $lifetime, $app);
            });
        });
    }
}
