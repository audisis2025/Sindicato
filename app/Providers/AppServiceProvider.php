<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// MODELOS REALES DEL SISTEMA
use App\Models\User;
use App\Models\Procedure;
use App\Models\ProcedureRequest;
use App\Models\News;
use Livewire\Volt\Volt;

// OBSERVERS REALES
use App\Observers\UserObserver;
use App\Observers\ProcedureObserver;
use App\Observers\ProcedureRequestObserver;
use App\Observers\NewsObserver;

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
        Volt::mount([
            resource_path('views/livewire'),
        ]);
        Volt::mount('livewire/auth/forgot-password');

        // Observers disponibles en tu proyecto
        User::observe(UserObserver::class);
        Procedure::observe(ProcedureObserver::class);
        ProcedureRequest::observe(ProcedureRequestObserver::class);
        News::observe(NewsObserver::class);
    }
}
