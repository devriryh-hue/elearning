<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Quizze;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(\App\Application\Bus\CommandBus::class, \App\Application\Bus\LaravelBus::class);
        $this->app->bind(\App\Application\Bus\QueryBus::class, \App\Application\Bus\LaravelBus::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Route::model('quiz', Quizze::class);
    }
}
