<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        Inertia::share('auth.user', function(){
            return ['name' => Auth::user() ? Auth::user()->name : '','role'=> Auth::user() ? Auth::user()->user_type : 2];
        });
    }
}
